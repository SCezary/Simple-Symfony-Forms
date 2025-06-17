<?php

namespace App\Form\Type\Template\Fields;

use App\Entity\TemplateField;

use App\Form\DataTransformer\TemplateFieldDateTimeTransformer;
use App\Form\DataTransformer\TemplateFieldDateTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class TemplateFieldDateTimeType extends AbstractTemplateFieldType
{
    public function __construct(
        TemplateFieldDateTimeTransformer $transformer,
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

        $builder->add('value', DateTimeType::class, [
            'date_format' => 'dd-MM-yyyy HH:mm:ss',
            'data' => new \DateTime($builder->getData()),
            'label' => $templateField->getLabel(),
            'required' => $templateField->getRequired(),
        ]);
    }
}