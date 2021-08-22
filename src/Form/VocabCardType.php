<?php

namespace App\Form;

use App\Entity\ContextSentence;
use App\Entity\LanguageLevel;
use App\Entity\Tag;
use App\Entity\VocabCard;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VocabCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wordToTranslate')
//            ->add('tags', CollectionType::class, [
//                'entry_type' => Tag::class,
//                'allow_add' => true,
//                'allow_delete' => true,
//            ])
            ->add('englishWord')
            ->add('alternativeWritings', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('synonyms', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ] )
            ->add('translations', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ] )
            ->add('userNotes')
            ->add('translationLocale')
            ->add('cardLocale')
            ->add('difficultyLevels', EntityType::class, [
                'class' => LanguageLevel::class,
                'multiple' => true,
            ])
            ->add('contextSentences', EntityType::class, [
                'class' => ContextSentence::class,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VocabCard::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }

    public function getBlockPrefix(): string
    {
        return "api_vocab_card";
    }




}
