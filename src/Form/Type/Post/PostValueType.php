<?php

namespace App\Form\Type\Post;

use App\Entity\PostValue;
use App\Entity\Enums\TemplateFieldTypeEnum;
use App\Entity\Template;
use App\Entity\TemplateField;

use App\Form\Type\Template\Fields\TemplateFieldDateTimeType;
use App\Form\Type\Template\Fields\TemplateFieldDateType;
use App\Form\Type\Template\Fields\TemplateFieldSelectType;
use App\Form\Type\Template\Fields\TemplateFieldTextType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostValueType extends AbstractType
{
    public function getTemplateFieldMap(): array
    {
        return [
            TemplateFieldTypeEnum::SELECT->value => TemplateFieldSelectType::class,
            TemplateFieldTypeEnum::TEXT->value => TemplateFieldTextType::class,
            TemplateFieldTypeEnum::DATE->value => TemplateFieldDateType::class,
            TemplateFieldTypeEnum::DATETIME->value => TemplateFieldDateTimeType::class,
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            /** @var PostValue $postValue */
            $postValue = $event->getData();

            $form = $event->getForm();
            /** @var Template $template  */
            if ($postValue instanceof PostValue && ($templateField = $postValue->getTemplateField()) instanceof TemplateField) {
                $field = $this->getTemplateFieldMap()[$templateField->getType()->value] ?? null;

                if ($field) {
                    $form->add('value', $field, [
                        'data' => $postValue->getValue(),
                        'templateField' => $templateField,
                        'label' => false,
                    ]);
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostValue::class,
        ]);
    }
}
