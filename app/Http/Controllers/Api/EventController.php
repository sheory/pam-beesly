<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $service;

    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request) {
        try {
            return $this->service->store($request);
        } catch(\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
