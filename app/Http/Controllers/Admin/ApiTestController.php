<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApiTestController extends Controller
{
    public function index(): View
    {
        return view('admin.api.test-console');
    }
}

