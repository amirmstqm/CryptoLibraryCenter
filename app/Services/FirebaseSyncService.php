<?php

namespace App\Services;

use App\Models\Algorithm;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseSyncService
{
    /**
     * Firebase project ID from config
     */
    private string $projectId;

    /**
     * Firebase REST API base URL
     */
    private string $baseUrl;

    public function __construct()
    {
        $this->projectId = config('firebase.project_id');
        $this->baseUrl   = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
    }

    /**
     * Sync both 'libraries' and 'articles' collections from Firebase to MySQL.
     * Returns array with counts: ['synced' => int, 'skipped' => int, 'errors' => int]
     */
    public function syncAll(): array
    {
        $results = ['synced' => 0, 'skipped' => 0, 'errors' => 0];

        // Sync libraries collection
        $libraryResults  = $this->syncCollection('libraries');
        $results['synced']  += $libraryResults['synced'];
        $results['skipped'] += $libraryResults['skipped'];
        $results['errors']  += $libraryResults['errors'];

        // Sync articles collection (merged with libraries in details page)
        $articleResults  = $this->syncCollection('articles');
        $results['synced']  += $articleResults['synced'];
        $results['skipped'] += $articleResults['skipped'];
        $results['errors']  += $articleResults['errors'];

        Log::info('Firebase sync completed', $results);

        return $results;
    }

    /**
     * Sync a single Firestore collection to MySQL.
     */
    private function syncCollection(string $collection): array
    {
        $results = ['synced' => 0, 'skipped' => 0, 'errors' => 0];

        try {
            $response = Http::get("{$this->baseUrl}/{$collection}");

            if (!$response->successful()) {
                Log::error("Firebase: Failed to fetch {$collection}", [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                $results['errors']++;
                return $results;
            }

            $data = $response->json();
            $documents = $data['documents'] ?? [];

            foreach ($documents as $document) {
                try {
                    $synced = $this->syncDocument($document, $collection);
                    if ($synced) {
                        $results['synced']++;
                    } else {
                        $results['skipped']++;
                    }
                } catch (\Exception $e) {
                    Log::error("Firebase: Error syncing document", [
                        'collection' => $collection,
                        'error'      => $e->getMessage(),
                    ]);
                    $results['errors']++;
                }
            }

        } catch (\Exception $e) {
            Log::error("Firebase: Exception syncing {$collection}", ['error' => $e->getMessage()]);
            $results['errors']++;
        }

        return $results;
    }

    /**
     * Sync a single Firestore document into the algorithms table.
     * Uses upsert so it creates or updates based on firebase_id.
     */
    private function syncDocument(array $document, string $collection): bool
    {
        // Extract Firebase document ID from the name path
        // Format: "projects/{proj}/databases/(default)/documents/{collection}/{id}"
        $nameParts  = explode('/', $document['name']);
        $firebaseId = end($nameParts);

        $fields = $document['fields'] ?? [];

        // Parse all fields from Firestore format
        $parsed = $this->parseFields($fields);

        // Articles only contain content fields — update existing library records only
        if ($collection === 'articles') {
            Algorithm::where('firebase_id', $firebaseId)->update([
                'overview'          => $parsed['overview']          ?? null,
                'limitation'        => $parsed['limitation']        ?? null,
                'installation_step' => $parsed['installation-step'] ?? null,
                'testing'           => $parsed['testing']           ?? null,
                'image'             => $parsed['image']             ?? null,
                'firebase_updated_at' => now(),
            ]);
            return true;
        }

        // Map Firebase field names → MySQL column names
        Algorithm::updateOrCreate(
            ['firebase_id' => $firebaseId],
            [
                'name'              => $parsed['name']              ?? null,
                'developer'         => $parsed['developer']         ?? null,
                'language'          => $parsed['language']          ?? null,
                'latest_version'    => $parsed['latest-version']    ?? null,
                'latest_release'    => $parsed['latest-release']    ?? null,
                'license'           => $parsed['license']           ?? null,
                'open_source'       => $parsed['open-source']       ?? false,
                'github'            => $parsed['github']            ?? null,
                'show'              => $parsed['show']              ?? true,
                'pqc_algorithm'     => $parsed['pqc-algorithm']     ?? null,
                'overview'          => $parsed['overview']          ?? null,
                'limitation'        => $parsed['limitation']        ?? null,
                'installation_step' => $parsed['installation-step'] ?? null,
                'testing'           => $parsed['testing']           ?? null,
                'image'             => $parsed['image']             ?? null,
                'firebase_updated_at' => now(),
            ]
        );

        return true;
    }

    /**
     * Parse Firestore REST API field format into a plain PHP array.
     *
     * Firestore REST fields look like:
     * {
     *   "name": { "stringValue": "OpenSSL" },
     *   "open-source": { "booleanValue": true },
     *   "pqc-algorithm": { "arrayValue": { "values": [ {"stringValue": "Kyber"} ] } }
     * }
     */
    private function parseFields(array $fields): array
    {
        $result = [];

        foreach ($fields as $key => $valueWrapper) {
            $result[$key] = $this->parseValue($valueWrapper);
        }

        return $result;
    }

    /**
     * Recursively parse a single Firestore value wrapper.
     */
    private function parseValue(array $valueWrapper): mixed
    {
        if (isset($valueWrapper['stringValue'])) {
            return $valueWrapper['stringValue'];
        }

        if (isset($valueWrapper['integerValue'])) {
            return (int) $valueWrapper['integerValue'];
        }

        if (isset($valueWrapper['doubleValue'])) {
            return (float) $valueWrapper['doubleValue'];
        }

        if (isset($valueWrapper['booleanValue'])) {
            return (bool) $valueWrapper['booleanValue'];
        }

        if (isset($valueWrapper['nullValue'])) {
            return null;
        }

        if (isset($valueWrapper['arrayValue'])) {
            $values = $valueWrapper['arrayValue']['values'] ?? [];
            return array_map(fn($v) => $this->parseValue($v), $values);
        }

        if (isset($valueWrapper['mapValue'])) {
            return $this->parseFields($valueWrapper['mapValue']['fields'] ?? []);
        }

        if (isset($valueWrapper['timestampValue'])) {
            return $valueWrapper['timestampValue'];
        }

        return null;
    }
}
