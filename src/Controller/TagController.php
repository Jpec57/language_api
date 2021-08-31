<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/card-counts', name: 'get_tagged_srs_card_count', methods: ["GET"])]
    public function getSRSCardCountsByTagAction(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $tags = $viewer->getTags();
        $cards = $this->tagRepository->findCardCountByTagAndUser($viewer->getId(), true);
        return $this->json($cards, JsonResponse::HTTP_OK, [], ['groups' => ['default']]);
    }
}
