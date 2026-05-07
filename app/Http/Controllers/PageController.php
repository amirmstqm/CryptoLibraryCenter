<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Show the Home page.
     */
    public function home()
    {
        return view('pages.home');
    }

    /**
     * Show the Libraries listing page.
     */
    public function libraries()
    {
        return view('pages.libraries');
    }

    /**
     * Show the About page.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Show the Library Details page.
     * The library ID is passed as a query param (?id=xxx) and read by JS.
     */
    public function details(Request $request)
    {
        return view('pages.details');
    }
}
