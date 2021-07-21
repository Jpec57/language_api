<?php

namespace App\Controller;

use App\Entity\DTO\SRSReview;
use App\Entity\User;
use App\Entity\VocabCard;
use App\Form\SRSReviewType;
use App\Form\VocabCardType;
use App\Repository\SRSCardRepository;
use App\Repository\VocabCardRepository;
use App\Service\SrsCardService;
use App\Trait\FormValidationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/srs-cards')]
class SRSCardController extends AbstractController
{
    private SrsCardService $srsCardService;
    private SRSCardRepository $SRSCardRepository;
    use FormValidationTrait;

    public function __construct(SrsCardService $srsCardService, SRSCardRepository $SRSCardRepository)
    {
        $this->srsCardService = $srsCardService;
        $this->SRSCardRepository = $SRSCardRepository;
    }

    #[Route('/review', name: 'get_review_srs_card', methods: ["GET"])]
    public function getReviewSRSCardAction(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $cards = $this->getDoctrine()->getRepository(VocabCard::class)->findAvailableCards($viewer, new \DateTime());

        return $this->json($cards, JsonResponse::HTTP_OK, [], ['groups' => ['default']]);
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
        return $this->json($modifiedCards, JsonResponse::HTTP_OK, [], ['groups' => ['default']]);
    }
}
