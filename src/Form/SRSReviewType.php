<?php

namespace App\Form;

use App\Entity\DTO\SRSCardReview;
use App\Entity\DTO\SRSReview;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SRSReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cards', EntityType::class, [
                'class'=> SRSCardReview::class,
                'multiple'=> true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SRSReview::class,
        ]);
    }
}
