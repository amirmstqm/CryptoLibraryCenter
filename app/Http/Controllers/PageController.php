<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Show the public Landing page.
     */
    public function landing()
    {
        return view('pages.landing');
    }

    /**
     * Show the Home page.
     */
    public function home()
    {
        return view('pages.home');
    }

    /**
     * Show the Libraries listing page.
     *
     * Fetches all visible libraries from the database (algorithms table),
     * computes normalised PQC algorithm arrays, and passes both the
     * Eloquent collection (for Blade rendering) and a JSON-safe array
     * (for the client-side comparison module) to the view.
     */
    public function libraries()
    {
        $libraries = Library::where('show', true)
            ->orderBy('name')
            ->get();

        // Build a JS-compatible array that mirrors the old Firebase payload.
        $librariesJson = $libraries->map(fn (Library $lib) => $lib->toFrontendArray())->values();

        return view('pages.libraries', compact('libraries', 'librariesJson'));
    }

    /**
     * Show the Library Details page.
     *
     * Resolves the library by its Firebase document ID (firebase_id column),
     * which is passed as ?id=<firebase_id>.  Returns 404 when not found.
     */
    public function details(Request $request)
    {
        $id = $request->query('id');

        if (! $id) {
            return redirect()->route('libraries');
        }

        $library = Library::where('firebase_id', $id)->firstOrFail();

        return view('pages.details', compact('library'));
    }

    /**
     * Show the About page.
     */
    public function about()
    {
        return view('pages.about');
    }
}
