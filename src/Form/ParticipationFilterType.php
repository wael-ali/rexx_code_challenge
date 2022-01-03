<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Participation;
use App\Form\ImportedFileEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ParticipationFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employee', EntityType::class, [
                'label' => 'Employee',
                'choice_label' => 'name',
                'class' => User::class,
                'mapped' => false,
                'required' => false,
            ])
            ->add('eventDate', EntityType::class, [
                'label' => 'Event Date',
                'class' => Event::class,
                'choice_label' => function ($event) {
                    return $event->getDisplayDate();
                },

                'mapped' => false,
                'required' => false,
            ])
            ->add('eventName', EntityType::class, [
                'label' => 'Event Name',
                'class' => Event::class,
                'choice_label' => function ($event) {
                    return $event->getName();
                },

                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}