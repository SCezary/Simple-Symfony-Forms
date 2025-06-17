<?php

namespace App\Entity\Enums;

use Symfony\Contracts\Translation\TranslatorInterface;

enum TemplateFieldTypeEnum: string
{
    case TEXT = 'text';
    case DATE = 'date';
    case DATETIME = 'datetime';
    case SELECT = 'select';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return match ($this) {
            self::TEXT => 'text',
            self::DATE => 'date',
            self::DATETIME => 'datetime',
            self::SELECT => 'select',
        };
    }
}
