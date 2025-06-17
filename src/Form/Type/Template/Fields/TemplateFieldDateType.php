<?php

namespace App\Form\Type\Template\Fields;

use App\Entity\TemplateField;
use App\Form\DataTransformer\TemplateFieldDateTransformer;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class TemplateFieldDateType extends AbstractTemplateFieldType
{
    public function __construct(
        TemplateFieldDateTransformer $transformer,
    ) {
        parent::__construct($transformer);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $templateField = $options['templateField'] ?? null;

        if (!$templateField instanceof TemplateField) {
            return;
        }

        $builder->add('value', DateType::class, [
            'label' => $templateField->getLabel(),
            'required' => $templateField->getRequired(),
            'data' => $builder->getData(),
            'format' => 'dd-MM-yyyy',
        ]);
    }
}