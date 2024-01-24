<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TestController extends Controller
{
    public function test(Request $request): Response {
        return Inertia::render('test');
    }
}
