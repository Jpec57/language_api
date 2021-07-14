<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VocabCardController extends AbstractController
{
    #[Route('/vocab-cards', name: 'create_vocab_card', methods: ["POST"])]
    public function createCard(): Response
    {
        return new JsonResponse(['message' => "Ok"]);
    }
}
