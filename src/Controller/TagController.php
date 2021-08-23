<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tags')]
class TagController extends AbstractController
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    #[Route('/', name: 'list_tags')]
    public function homepage(): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $tags = $viewer->getTags();
        return $this->json($tags, JsonResponse::HTTP_OK, [], ['groups'=> ['default']]);
    }
}
