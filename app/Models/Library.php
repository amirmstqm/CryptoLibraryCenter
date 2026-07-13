<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    protected $table = 'algorithms';

    protected $fillable = [
        'firebase_id', 'name', 'developer', 'language', 'category',
        'latest_version', 'latest_release', 'license', 'open_source',
        'github', 'show', 'pqc_algorithm', 'overview', 'limitation',
        'installation_step', 'testing', 'image',
    ];

    protected $casts = [
        'pqc_algorithm'     => 'array',
        'installation_step' => 'array',
        'testing'           => 'array',
        'image'             => 'array',
        'open_source'       => 'boolean',
        'show'              => 'boolean',
    ];

    /**
     * Normalize the PQC algorithms into a clean array,
     * mirroring the JS normalizePqcAlgorithms() function.
     */
    public function getPqcAlgorithmsAttribute(): array
    {
        $raw = $this->pqc_algorithm;

        if (! $raw) {
            return [];
        }

        if (is_array($raw)) {
            return array_values(array_map('trim', $raw));
        }

        if (is_string($raw)) {
            return array_values(array_map('trim', explode(',', $raw)));
        }

        return [];
    }

    /**
     * Whether the library supports at least one real PQC algorithm
     * (i.e. pqcAlgorithms is non-empty and does not contain only "PQC Unsupported").
     */
    public function getHasPqcSupportAttribute(): bool
    {
        foreach ($this->pqcAlgorithms as $alg) {
            if (stripos($alg, 'pqc unsupported') !== false) {
                return false;
            }
        }

        return ! empty($this->pqcAlgorithms);
    }

    /**
     * Serialise to array for JSON embedding in Blade views.
     * Keys mirror what the old Firebase/JS code expected.
     */
    public function toFrontendArray(): array
    {
        return [
            'id'             => $this->firebase_id,
            'name'           => $this->name,
            'normalizedName' => strtolower($this->name),
            'developer'      => $this->developer,
            'language'       => $this->language,
            'latest-version' => $this->latest_version,
            'latest-release' => $this->latest_release,
            'license'        => $this->license,
            'open-source'    => $this->open_source,
            'github'         => $this->github,
            'pqcAlgorithms'  => $this->pqcAlgorithms,
            'pqc-algorithm'  => $this->pqcAlgorithms,
        ];
    }
}
