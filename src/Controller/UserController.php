<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/me', name: 'personal_info')]
    public function index(): Response
    {
        $viewer = $this->getUser();
        return $this->json($viewer);
    }
}
