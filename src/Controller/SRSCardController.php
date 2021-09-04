<?php

namespace App\Controller;

use App\Entity\DTO\SRSReview;
use App\Entity\SRSCard;
use App\Entity\User;
use App\Entity\VocabCard;
use App\Form\SRSReviewType;
use App\Form\VocabCardType;
use App\Repository\SRSCardRepository;
use App\Repository\TagRepository;
use App\Repository\VocabCardRepository;
use App\Service\SrsCardService;
use App\Traits\FormValidationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/srs-cards')]
class SRSCardController extends AbstractController
{
    private SrsCardService $srsCardService;
    private EntityManagerInterface $entityManager;
    private SRSCardRepository $SRSCardRepository;
    private VocabCardRepository $vocabCardRepo;

    use FormValidationTrait;

    public function __construct(EntityManagerInterface $entityManager, SrsCardService $srsCardService, SRSCardRepository $SRSCardRepository, VocabCardRepository$vocabCardRepo)
    {
        $this->srsCardService = $srsCardService;
        $this->SRSCardRepository = $SRSCardRepository;
        $this->vocabCardRepo = $vocabCardRepo;
        $this->entityManager = $entityManager;
    }


    #[Route('/tagged/{tagId}', name: 'get_tagged_srs_card', methods: ["GET"])]
    public function getTaggedSRSCardAction(Request $request, int $tagId): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $locales = $request->get('locales', []);
        $cards = $this->SRSCardRepository->findByTag($tagId, $viewer, true, $locales);
        return $this->json($cards, JsonResponse::HTTP_OK, [], ['groups' => ['default', 'srscard_tag']]);
    }
    
    #[Route('/scheduled/lang/{languageCode}', name: 'get_scheduled_srs_card_by_lang', methods: ["GET"])]
    public function getSRSCardScheduledByLangAction(Request $request, $languageCode): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $cards = $this->vocabCardRepo->findOrderedCardSchedule($viewer);
        return $this->json($cards, JsonResponse::HTTP_OK, [], ['groups' => ['default']]);
    }

    #[Route('/scheduled', name: 'get_scheduled_srs_card', methods: ["GET"])]
    public function getSRSCardScheduledAction(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $cards = $this->vocabCardRepo->findOrderedCardSchedule($viewer);
        return $this->json($cards, JsonResponse::HTTP_OK, [], ['groups' => ['default']]);
    }

    #[Route('/{id}/activation/toggle', name: 'toggle_activate_srs_card', methods: ["GET"])]
    public function toggleActivationSRSCardAction(Request $request, $id): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $card = $this->vocabCardRepo->find($id);
        if (!$card){
            return new JsonResponse(['message'=> 'Not found', 404]);
        }
        if (!$viewer || $card->getUser()->getId() != $viewer->getId()){
            throw new UnauthorizedHttpException("You are not allowed to see this.");
        }
        $card->setIsActivated(!$card->getIsActivated());
        $this->entityManager->flush();
        return $this->json($card, JsonResponse::HTTP_OK, [], ['groups' => ['default']]);
    }

    #[Route('/awaiting', name: 'get_awaiting_srs_card', methods: ["GET"])]
    public function getAwaitingSRSCardAction(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $locales = $request->get('locales', []);
        $cards = $this->vocabCardRepo->findAwaitingCards($viewer, $locales);
        return $this->json($cards, JsonResponse::HTTP_OK, [], ['groups' => ['default']]);
    }

    #[Route('/review', name: 'get_review_srs_card', methods: ["GET"])]
    public function getReviewSRSCardAction(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $locales = $request->get('locales', []);
        $cards = $this->vocabCardRepo->findAvailableCards($viewer, new \DateTime(), $locales);
        return $this->json($cards, JsonResponse::HTTP_OK, [], ['groups' => ['default']]);
    }

    #[Route('/review/count', name: 'get_review_srs_card_count', methods: ["GET"])]
    public function getReviewSRSCardCountAction(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $locales = $request->get('locales', []);
        $cards = $this->vocabCardRepo->findAvailableCardCount($viewer, new \DateTime(), $locales);
        return $this->json($cards, JsonResponse::HTTP_OK);
    }


    #[Route('/review', name: 'review_srs_card', methods: ["POST"])]
    public function reviewSRSCardAction(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $srsReview = new SRSReview($viewer);
        $form = $this->createForm(SRSReviewType::class, $srsReview);
        $form->submit($data);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->json(['errors' => $this->getErrorsFromForm($form)], JsonResponse::HTTP_BAD_REQUEST);
        }
        $modifiedCards = $this->srsCardService->modifySrsCardsAccordingToReview($srsReview);
        $viewer->updateStreakAndReviewDate();
        return $this->json($modifiedCards, JsonResponse::HTTP_OK, [], ['groups' => ['default']]);
    }
}
