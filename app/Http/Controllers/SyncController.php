<?php

namespace App\Http\Controllers;

use App\Models\Algorithm;
use App\Services\FirebaseSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SyncController extends Controller
{
    public function __construct(private FirebaseSyncService $syncService) {}

    /**
     * POST /sync/firebase
     * Webhook called by Firebase or manually to trigger immediate sync.
     * Optionally protected by a secret token in the request header.
     */
    public function webhook(Request $request): JsonResponse
    {
        // Optional: protect with a secret token
        $secret = config('firebase.webhook_secret');
        if ($secret && $request->header('X-Sync-Secret') !== $secret) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $results = $this->syncService->syncAll();

            return response()->json([
                'success' => true,
                'message' => 'Firebase → MySQL sync completed.',
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            Log::error('Sync webhook failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /sync/status
     * Returns current sync status and MySQL record counts.
     */
    public function status(): JsonResponse
    {
        $total       = Algorithm::count();
        $visible     = Algorithm::visible()->count();
        $lastSynced  = Algorithm::max('firebase_updated_at');

        return response()->json([
            'total_records'   => $total,
            'visible_records' => $visible,
            'last_synced_at'  => $lastSynced,
            'status'          => $total > 0 ? 'synced' : 'empty',
        ]);
    }

    /**
     * GET /api/libraries
     * Returns all visible libraries from MySQL as JSON (for the frontend).
     * Supports ?search=, ?language=, ?pqc=, ?pqc_supported= query params.
     */
    public function libraries(Request $request): JsonResponse
    {
        $query = Algorithm::visible();

        // Search by name
        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by language
        if ($language = $request->query('language')) {
            $query->hasLanguage($language);
        }

        // Filter by PQC algorithm (e.g. ?pqc=Kyber)
        if ($pqc = $request->query('pqc')) {
            $query->hasAlgorithm($pqc);
        }

        // Filter by PQC supported yes/no
        if ($pqcSupported = $request->query('pqc_supported')) {
            $algorithms = $query->get()->filter(function ($lib) use ($pqcSupported) {
                return $pqcSupported === 'yes'
                    ? $lib->supportsPqc()
                    : !$lib->supportsPqc();
            });

            return response()->json($this->formatLibraries($algorithms));
        }

        $libraries = $query->orderBy('name')->get();

        return response()->json($this->formatLibraries($libraries));
    }

    /**
     * GET /api/libraries/{firebaseId}
     * Returns a single library by Firebase ID from MySQL.
     */
    public function libraryDetail(string $firebaseId): JsonResponse
    {
        $library = Algorithm::where('firebase_id', $firebaseId)->first();

        if (!$library) {
            return response()->json(['error' => 'Library not found.'], 404);
        }

        return response()->json($this->formatDetail($library));
    }

    // -----------------------------------------------
    // Private helpers — format MySQL records to match
    // the shape the frontend JavaScript already expects
    // -----------------------------------------------

    private function formatLibraries($libraries): array
    {
        return $libraries->map(fn($lib) => [
            'id'              => $lib->firebase_id,
            'name'            => $lib->name,
            'developer'       => $lib->developer,
            'language'        => $lib->language,
            'latest-version'  => $lib->latest_version,
            'latest-release'  => $lib->latest_release,
            'license'         => $lib->license,
            'open-source'     => $lib->open_source,
            'github'          => $lib->github,
            'pqc-algorithm'   => $lib->pqc_algorithms,
            'show'            => $lib->show,
        ])->values()->toArray();
    }

    private function formatDetail(Algorithm $lib): array
    {
        return [
            'id'                => $lib->firebase_id,
            'name'              => $lib->name,
            'developer'         => $lib->developer,
            'language'          => $lib->language,
            'latest-version'    => $lib->latest_version,
            'latest-release'    => $lib->latest_release,
            'license'           => $lib->license,
            'open-source'       => $lib->open_source,
            'github'            => $lib->github,
            'pqc-algorithm'     => $lib->pqc_algorithms,
            'overview'          => $lib->overview,
            'limitation'        => $lib->limitation,
            'installation-step' => $lib->installation_step,
            'testing'           => $lib->testing,
            'image'             => $lib->image,
        ];
    }
}
