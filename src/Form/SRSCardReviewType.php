<?php

namespace App\Form;

use App\Entity\DTO\SRSCardReview;
use App\Entity\DTO\SRSReview;
use App\Entity\SRSCard;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SRSCardReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('card', EntityType::class, [
                'class'=> SRSCard::class
            ])
            ->add('errorCount', IntegerType::class, [
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SRSCardReview::class,
            'csrf_protection' => false,
        ]);
    }
}
