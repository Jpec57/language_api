<?php


namespace App\Service;


use App\Entity\Tag;
use App\Entity\User;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

class TagService
{

    private TagRepository $tagRepository;
    private EntityManagerInterface $entityManager;


    public function __construct(EntityManagerInterface $entityManager, TagRepository $tagRepository)
    {
        $this->entityManager = $entityManager;
        $this->tagRepository = $tagRepository;
    }

    public function getOrCreateTagFromLabels(User $viewer, array $tags): array
    {
        $tags = array_unique($tags);
        if (empty($tags)){
            $tags = ["default"];
        }
        /** @var Tag[] $existingTags */
        $existingTags = $this->entityManager->getRepository(Tag::class)
            ->findBy(['label' => $tags, 'user' => $viewer]);
        $existingTagLabels = [];
        foreach ($existingTags as $existingTag){
            $existingTagLabels[] = $existingTag->getLabel();
        }
        if (count($existingTagLabels) < count($tags)){
            foreach ($tags as $tag){
                if (!in_array($tag, $existingTagLabels)){
                    $newTag = new Tag();
                    $newTag->setUser($viewer);
                    $newTag->setLabel($tag);
                    $this->entityManager->persist($newTag);
                    $existingTags[] = $newTag;
                }
            }
            $this->entityManager->flush();
        }
        return $existingTags;
    }

}