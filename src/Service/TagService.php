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

    public function getOrCreateTagFromLabels(User $viewer, array $tags, array $locales): array
    {
        $tags = array_unique($tags);
        if (count($locales) < 2){
            throw new \RuntimeException("You have to provide at least two locales");
        }
        if (empty($tags)){
            $tags = ["default"];
        }
        $existingTags = $this->tagRepository->findByLabelsAndLocalesForUser($viewer->getId(), $tags, $locales);
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
                    $newTag->setLocale1($locales[0]);
                    $newTag->setLocale2($locales[1]);
                    $this->entityManager->persist($newTag);
                    $existingTags[] = $newTag;
                }
            }
            $this->entityManager->flush();
        }
        return $existingTags;
    }

}