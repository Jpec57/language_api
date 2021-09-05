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
    name: 'app:add-default-tag',
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
        $io->info( count($cards) . ' cards');
        foreach ($cards as $card){
            if (is_null($card->getTags()) || empty($card->getTags()) || count($card->getTags()) < 1){
                $io->caution("Card with empty tag");
                $user = $card->getUser();
                $defaultTag = $this->tagRepository->findByLabelsAndLocalesForUser($user->getId(), ["default"], [$card->getCardLocale(), $card->getTranslationLocale()]);
                if (!$defaultTag || empty($defaultTag)){
                    $defaultTag = new Tag();
                    $defaultTag->setLabel("default")
                        ->setLocale1($card->getCardLocale())
                        ->setLocale2($card->getTranslationLocale())
                        ->setUser($user);
                    $this->entityManager->persist($defaultTag);
                    $io->note("Creating default tag");
                } else {
                    $defaultTag = $defaultTag[0];
                }
                $card->addTag($defaultTag);
                $this->entityManager->flush();
            } else {
                $tags = $card->getTags();
                $tagStr = "";
                foreach ($tags as $tag){
                    $tagStr .= $tag->getId() . " " .$tag->getLabel() . "\n";
                }
                $io->info( $card->getWordToTranslate() . ' with TAGS ' . $tagStr);
            }
        }

        $io->success('Done.');

        return Command::SUCCESS;
    }
}
