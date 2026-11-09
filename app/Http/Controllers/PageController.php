<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        // A static page. Developer information comes from configuration, not a query.
        return view('pages.about');
    }
}
