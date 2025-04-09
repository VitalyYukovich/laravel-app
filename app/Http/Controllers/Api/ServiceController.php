<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return Service::with('category')->paginate(10);
    }

    public function show(Service $service)
    {
        return $service->load('category');
    }
}