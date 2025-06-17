<?php

namespace App\Service;

class EntityService
{
    public function toSystemName(string $string): string
    {
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        $stringElements = array_map(fn($el) => ucfirst($el), explode(' ', trim($string)));
        return lcfirst(implode('', $stringElements));
    }
}