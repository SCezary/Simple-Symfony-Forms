<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class TemplateFieldTransformer implements DataTransformerInterface
{

    public function reverseTransform(mixed $value)
    {
        if (is_array($value) && array_key_exists('value', $value)) {
            return $value['value'];
        }

        return $value;
    }

    public function transform(mixed $value)
    {
        // TODO: Implement transform() method.
    }
}