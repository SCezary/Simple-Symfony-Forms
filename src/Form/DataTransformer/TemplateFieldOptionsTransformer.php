<?php

namespace App\Form\DataTransformer;

use App\Entity\Enums\TemplateFieldTypeEnum;
use App\Entity\TemplateField;
use Symfony\Component\Form\DataTransformerInterface;

class TemplateFieldOptionsTransformer implements DataTransformerInterface
{
    public function transform(mixed $value)
    {
        return $value;
    }

    public function reverseTransform(mixed $value)
    {
        if ($value instanceof TemplateField) {
            $options = $value->getOptions();
            if (!empty($options['select'])) {
                $selectValue = preg_replace('/[^A-Za-z0-9,]/', '', $options['select']);
                $selectOptions = array_map(fn($el) => [
                    'label' => ucfirst($el),
                    'value' => $el,
                ], explode(',', $selectValue));

                $value->setOptions($selectOptions);
            }
        }

        return $value;
    }
}