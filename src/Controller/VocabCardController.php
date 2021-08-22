<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\VocabCard;
use App\Form\VocabCardType;
use App\Repository\SRSCardRepository;
use App\Repository\TagRepository;
use App\Repository\VocabCardRepository;
use App\Service\TagService;
use App\Traits\FormValidationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vocab-cards')]
class VocabCardController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private VocabCardRepository $vocabCardRepository;
    private SRSCardRepository $SRSCardRepository;
    private TagService $tagService;
    use FormValidationTrait;

    public function __construct(TagService $tagService, EntityManagerInterface $entityManager, VocabCardRepository $vocabCardRepository, SRSCardRepository $SRSCardRepository)
    {
        $this->entityManager = $entityManager;
        $this->vocabCardRepository = $vocabCardRepository;
        $this->SRSCardRepository = $SRSCardRepository;
        $this->tagService = $tagService;
    }


    #[Route('/', name: 'create_vocab_card', methods: ["POST"])]
    public function createCard(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $tags = array_key_exists('tags', $data) ? $data["tags"] : [];
        $vocabCard = new VocabCard($viewer);
        $form = $this->createForm(VocabCardType::class, $vocabCard);
        $form->submit($data);
        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->json(['errors' => $this->getErrorsFromForm($form)], JsonResponse::HTTP_BAD_REQUEST);
        }
        /** @var VocabCard $vocabCard */
        $vocabCard = $form->getData();
        $associatedTags = $this->tagService->getOrCreateTagFromLabels($viewer, $tags);
        $vocabCard->setTags($associatedTags);
        $this->entityManager->persist($vocabCard);
        $reverseCard = $vocabCard->createReversedCard();
        $this->entityManager->persist($reverseCard);
        $this->entityManager->flush();
        return $this->json([$vocabCard, $reverseCard], JsonResponse::HTTP_CREATED, [], ['groups' => ['default', 'srscard_user', 'srscard_tag']]);
    }


    #[Route('/', name: 'list_vocab_card', methods: ["GET"])]
    public function listCards(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $vocabCards = $this->vocabCardRepository->findBy(['user'=> $viewer], ['cardLocale' => "ASC", 'wordToTranslate' => 'ASC']);
        return $this->json($vocabCards, JsonResponse::HTTP_OK, [], ['groups' => ['default', 'srscard_user', 'srscard_tag']]);
    }

    #[Route('/search', name: 'list_vocab_card2', methods: ["GET"])]
    public function listCards2(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $limit = $request->get('limit');
        $page = $request->get('page');
        $vocabCards = $this->SRSCardRepository->searchCards($viewer, null, $page, $limit);
        return $this->json($vocabCards, JsonResponse::HTTP_OK, [], ['groups' => ['default', 'srscard_user', 'srscard_tag']]);
    }
}
