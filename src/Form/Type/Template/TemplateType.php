<?php

namespace App\Form\Type\Template;

use App\Entity\Template;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $form = $builder->getForm();

        /** @var Template $template */
        $template = $builder->getData();

        $builder->add('name', TextType::class, [
                'required' => true,
            ])->add('active', CheckboxType::class, [
                'required' => false,
                'label' => 'Active',
            ])->add('templateFields', CollectionType::class, [
                'entry_type' => TemplateFieldType::class,
                'required' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'label' => false,
            ])->add('save', SubmitType::class);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Template::class,
        ]);
    }
}