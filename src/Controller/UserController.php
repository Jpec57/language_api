<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/', name: 'homepage')]
    public function homepage(): Response
    {
        return new JsonResponse([
            'message' => "Hello from learning-language :D"
        ]);
    }

    #[Route('/me', name: 'personal_info')]
    public function index(): Response
    {
        $viewer = $this->getUser();
        return $this->json($viewer);
    }
}
