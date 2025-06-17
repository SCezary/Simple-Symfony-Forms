<?php

namespace App\Form\Type\Template\Fields;

use App\Entity\TemplateField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class TemplateFieldSelectType extends AbstractTemplateFieldType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $templateField = $options['templateField'] ?? null;

        if (!$templateField instanceof TemplateField) {
            return;
        }

        $options = $templateField->getOptions();
        if (count($options) > 0) {

            $builder->add('value', ChoiceType::class, [
                'label' => $templateField->getLabel(),
                'required' => $templateField->getRequired(),
                'choices' => $templateField->getOptionsAsAssocArray(),
                'data' => $builder->getData() ?? '',
            ]);
        }
    }

}