<?php

namespace App\Form\DataTransformer;

use DateTime;

class TemplateFieldDateTimeTransformer extends TemplateFieldTransformer
{
    public function reverseTransform(mixed $value)
    {
        $value = $value['value'] ?? null;
        if ($value instanceof DateTime) {
            return $value->format('Y-m-d h:i:s');
        }

        return null;
    }
}