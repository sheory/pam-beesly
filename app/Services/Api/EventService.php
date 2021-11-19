<?php

namespace App\Services\Api;

use App\Models\Event;
use App\Services\ServiceTrait;
use App\Validators\EventValidator;

class EventService
{
    use ServiceTrait;

    function model()
    {
        return new Event();
    }

    function validationRules()
    {
       return new EventValidator();
    }
}
