<?php

namespace App\Form;

use App\Entity\Inventory;
use App\Entity\Tape;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $member = $options['member'];
        $builder
            ->add('name')
            ->add(
                'tapes',
                null,
                [
                    'choices' => $member->getTapes(),
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'by_reference' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('member');
        $resolver->setDefaults([
            'data_class' => Inventory::class,
        ]);
    }
}
