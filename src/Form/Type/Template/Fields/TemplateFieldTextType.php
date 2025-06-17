<?php

namespace App\Form\Type\Template\Fields;

use App\Entity\TemplateField;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class TemplateFieldTextType extends AbstractTemplateFieldType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $templateField = $options['templateField'] ?? null;

        if (!$templateField instanceof TemplateField) {
            return;
        }

        $constraints = [];
        $templateFieldOptions = $templateField->getOptions();

        if (!empty($templateFieldOptions['minLength'])) {
            $constraints[] = new Length(['min' => $templateFieldOptions['minLength']]);
        }

        if (!empty($templateFieldOptions['maxLength'])) {
            $constraints[] = new Length(['max' => $templateFieldOptions['maxLength']]);
        }

        $builder->add('value', TextType::class, [
            'data' => $builder->getData(),
            'label' => $templateField->getLabel(),
            'required' => $templateField->getRequired(),
            'constraints' => $constraints,
        ]);

        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'templateField' => null,
        ]);
    }
}