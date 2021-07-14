<?php

namespace App\Form;

use App\Entity\VocabCard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VocabCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wordToTranslate')
            ->add('englishWord')
            ->add('alternativeWritings')
            ->add('synonyms')
            ->add('translations')
            ->add('userNotes')
            ->add('translationLocale')
            ->add('cardLocale')
            ->add('difficultyLevels')
            ->add('contextSentences')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VocabCard::class,
        ]);
    }
}
