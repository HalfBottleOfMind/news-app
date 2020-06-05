<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

class IndexController extends Controller
{
    /**
     * Render VUE SPA
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function __invoke(): Renderable
    {
        return view('index');
    }
}
