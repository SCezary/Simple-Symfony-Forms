<?php

namespace App\Form\Type\Template\Options;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateFieldSelectOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if (is_array($data)) {
                $data = array_filter($data, fn($el) => !empty($el['value']));
                $data = implode(',', array_map(fn($el) => $el['value'], $data));
            }

            $form->add('select',TextType::class, [
                'required' => false,
                'label' => false,
                'data' => $data,
            ]);
        });

        $builder->addModelTransformer(new CallbackTransformer([$this, 'transform'], [$this, 'reverseTransform']));

    }

    public function transform(mixed $value) {
        return $value;
    }

    public function reverseTransform(mixed $value) {
        return $value['select'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}