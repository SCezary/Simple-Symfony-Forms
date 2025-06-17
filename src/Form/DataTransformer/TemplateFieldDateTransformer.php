<?php

namespace App\Form\DataTransformer;

use DateTime;

class TemplateFieldDateTransformer extends TemplateFieldTransformer
{
    public function reverseTransform(mixed $value)
    {
        $value = $value['value'] ?? null;
        if ($value instanceof DateTime) {
            return $value->format('Y-m-d');
        }

        return null;
    }
}