<?php

namespace App\Form\Type\Template\Fields;

use App\Form\DataTransformer\TemplateFieldTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractTemplateFieldType extends AbstractType
{
    protected TemplateFieldTransformer $transformer;

    public function __construct(
        TemplateFieldTransformer $transformer,
    ) {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'templateField' => null,
            'data_class' => null,
        ]);
    }
}