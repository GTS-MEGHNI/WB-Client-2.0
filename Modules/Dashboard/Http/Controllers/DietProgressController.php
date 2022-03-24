<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DietProgressController extends Controller
{
    public function list(Request $request)
    {
        return $request->get('order')->dietProgress->toArray();
    }
}
