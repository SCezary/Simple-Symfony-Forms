<?php

namespace App\Form\Type\Template;

use App\Entity\Enums\TemplateFieldTypeEnum;
use App\Entity\TemplateField;
use App\Form\DataTransformer\TemplateFieldOptionsTransformer;
use App\Form\Type\Template\Options\TemplateFieldSelectOptionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsN;
use Symfony\Component\Validator\Constraints\Type;

class TemplateFieldType extends AbstractType
{
    public function __construct(
        private readonly TemplateFieldOptionsTransformer $transformer,
    ) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', TextType::class, [
                'label' => 'Label',
            ])
            ->add('required', CheckboxType::class, [
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => array_combine(array_map(fn($type) => $type->value, TemplateFieldTypeEnum::cases()),
                    TemplateFieldTypeEnum::cases())
            ])->add('order', HiddenType::class, [
                'constraints' => [
                    new Type('numeric'),
                ],
                'attr' => [
                    'data-field' => 'js-order'
                ]
            ]);

        $builder->add('options', FormType::class, [
            'label' => false,
            'required' => false,
        ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            /** @var TemplateField $templateField */
            $templateField = $event->getData();

            $form = $event->getForm();
            if ($templateField instanceof TemplateField) {
                $options = $templateField->getOptions();

                if ($templateField->getType() === TemplateFieldTypeEnum::SELECT) {
                    $form->get('options')->add('select', TemplateFieldSelectOptionType::class, [
                        'label' => 'Options (separated by comma)',
                        'required' => false,
                        'data' => $options,
                    ]);
                } else if  ($templateField->getType() === TemplateFieldTypeEnum::TEXT) {
                    $form->get('options')->add('minLength', NumberType::class, [
                        'label' => 'Minimum length',
                        'required' => false,
                        'data' => $options['minLength'] ?? null,
                    ]);
                    $form->get('options')->add('maxLength', NumberType::class, [
                        'label' => 'Maximum length',
                        'required' => false,
                        'data' => $options['maxLength'] ?? null,
                    ]);
                }
            }
        });

        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TemplateField::class,
        ]);
    }
}