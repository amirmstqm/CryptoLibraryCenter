<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private const CATEGORIES = [
        'Post-Quantum Cryptography',
        'Symmetric Encryption',
        'Asymmetric Encryption',
        'Hash Functions',
        'Digital Signatures',
    ];

    private const LANGUAGES = [
        'C', 'C++', 'C/C++', 'Java', 'Python', 'Rust', 'Go',
        'JavaScript', 'TypeScript', 'C#', 'Assembly', 'CUDA',
    ];

    // ─── Dashboard ───────────────────────────────────────────────────────────

    public function dashboard()
    {
        $totalLibraries = Library::count();

        $languages = Library::whereNotNull('language')
            ->pluck('language')
            ->flatMap(fn ($l) => array_map('trim', explode(',', $l)))
            ->filter()
            ->unique()
            ->count();

        $categories = Library::whereNotNull('category')
            ->distinct('category')
            ->count('category');

        // Top languages for bar chart
        $langCounts = Library::whereNotNull('language')
            ->pluck('language')
            ->flatMap(fn ($l) => array_map('trim', explode(',', $l)))
            ->filter()
            ->countBy()
            ->sortByDesc(fn ($v) => $v)
            ->take(6);

        // PQC vs Non-PQC for pie chart
        $allLibraries = Library::get();
        $pqcCount    = $allLibraries->filter(fn ($l) => $l->hasPqcSupport)->count();
        $nonPqcCount = $totalLibraries - $pqcCount;

        // Recently added
        $recentLibraries = Library::latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalLibraries', 'languages', 'categories',
            'langCounts', 'pqcCount', 'nonPqcCount', 'recentLibraries'
        ));
    }

    // ─── Add ─────────────────────────────────────────────────────────────────

    public function add()
    {
        return view('admin.add', [
            'categories' => self::CATEGORIES,
            'languages'  => self::LANGUAGES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'developer'        => 'nullable|string|max:255',
            'language'         => 'nullable|string|max:255',
            'category'         => 'nullable|string|max:255',
            'latest_version'   => 'nullable|string|max:100',
            'latest_release'   => 'nullable|string|max:100',
            'license'          => 'nullable|string|max:255',
            'github'           => 'nullable|url|max:500',
            'overview'         => 'nullable|string',
            'limitation'       => 'nullable|string',
            'open_source'      => 'boolean',
            'show'             => 'boolean',
            'pqc_algorithm'    => 'nullable|array',
            'pqc_algorithm.*'  => 'string',
        ]);

        $data['firebase_id']  = Str::uuid()->toString();
        $data['open_source']  = $request->boolean('open_source');
        $data['show']         = $request->boolean('show', true);
        $data['pqc_algorithm'] = $request->input('pqc_algorithm', []);

        Library::create($data);

        return redirect()->route('admin.browse')
            ->with('success', 'Library "' . $data['name'] . '" added successfully.');
    }

    // ─── Browse ───────────────────────────────────────────────────────────────

    public function browse(Request $request)
    {
        $search = $request->query('search', '');

        $query = Library::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('developer', 'like', "%{$search}%")
                  ->orWhere('language', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $libraries = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.browse', compact('libraries', 'search'));
    }

    // ─── Edit ─────────────────────────────────────────────────────────────────

    public function edit($id)
    {
        $library = Library::findOrFail($id);

        return view('admin.edit', [
            'library'    => $library,
            'categories' => self::CATEGORIES,
            'languages'  => self::LANGUAGES,
        ]);
    }

    public function update(Request $request, $id)
    {
        $library = Library::findOrFail($id);

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'developer'        => 'nullable|string|max:255',
            'language'         => 'nullable|string|max:255',
            'category'         => 'nullable|string|max:255',
            'latest_version'   => 'nullable|string|max:100',
            'latest_release'   => 'nullable|string|max:100',
            'license'          => 'nullable|string|max:255',
            'github'           => 'nullable|url|max:500',
            'overview'         => 'nullable|string',
            'limitation'       => 'nullable|string',
            'open_source'      => 'boolean',
            'show'             => 'boolean',
            'pqc_algorithm'    => 'nullable|array',
            'pqc_algorithm.*'  => 'string',
        ]);

        $data['open_source']   = $request->boolean('open_source');
        $data['show']          = $request->boolean('show', true);
        $data['pqc_algorithm'] = $request->input('pqc_algorithm', []);

        $library->update($data);

        return redirect()->route('admin.browse')
            ->with('success', 'Library "' . $data['name'] . '" updated successfully.');
    }

    // ─── Delete ───────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        $library = Library::findOrFail($id);
        $name    = $library->name;
        $library->delete();

        return redirect()->route('admin.browse')
            ->with('success', 'Library "' . $name . '" deleted successfully.');
    }
}
