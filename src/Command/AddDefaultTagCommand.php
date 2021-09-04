<?php

namespace App\Command;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Repository\VocabCardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'add-default-tag',
    description: 'Add "Default" tag',
)]
class AddDefaultTagCommand extends Command
{
    private $vocabCardRepository;
    private $tagRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, VocabCardRepository $vocabCardRepository, TagRepository $tagRepository, string $name = null)
    {
        $this->vocabCardRepository = $vocabCardRepository;
        $this->tagRepository = $tagRepository;
        $this->entityManager = $entityManager;
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $cards = $this->vocabCardRepository->findAll();
        foreach ($cards as $card){
            if (empty($card->getTags())){
                $io->caution("Card with empty tag");
                $user = $card->getUser();
                $defaultTag = $this->tagRepository->findOneBy(['user'=> $user, 'label'=> "default"]);
                if (!$defaultTag){
                    $defaultTag = new Tag();
                    $defaultTag->setLabel("default")
                        ->setUser($user);
                    $this->entityManager->persist($defaultTag);
                    $io->note("Creating default tag");
                }
                $card->addTag($defaultTag);
                $this->entityManager->flush();
            }
        }

        $io->success('Done.');

        return Command::SUCCESS;
    }
}
