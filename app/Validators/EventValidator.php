<?php

namespace App\Validators;

class EventValidator
{
    use ValidatorTrait;
    protected function rules($data = null)
    {
        return [
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:250',
            'begins_at' => 'required|date|after:now',
            'ends_at' => 'required|date|after:begins_at',
            'place' => 'nullable|string|max:150'
        ];
    }
}
