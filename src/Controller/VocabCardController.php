<?php

namespace App\Controller;

use App\Entity\VocabCard;
use App\Form\VocabCardType;
use App\Trait\FormValidationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VocabCardController extends AbstractController
{
    private $entityManager;
    use FormValidationTrait;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/vocab-cards', name: 'create_vocab_card', methods: ["POST"])]
    public function createCard(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $vocabCard = new VocabCard();
        $form = $this->createForm(VocabCardType::class, $vocabCard);
        $form->submit($data);
        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->json(['errors' => $this->getErrorsFromForm($form)], JsonResponse::HTTP_BAD_REQUEST);
        }
        $vocabCard = $form->getData();
        $this->entityManager->persist($vocabCard);
        $reverseCard = $vocabCard->createReversedCard();
        $this->entityManager->persist($reverseCard);
        $this->entityManager->flush();
        return $this->json([$vocabCard, $reverseCard], JsonResponse::HTTP_CREATED, [], ['groups' => ['default']]);
    }
}
