<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Algorithm extends Model
{
    use HasFactory;

    protected $table = 'algorithms';

    protected $fillable = [
        'firebase_id',
        'name',
        'developer',
        'language',
        'latest_version',
        'latest_release',
        'license',
        'open_source',
        'github',
        'show',
        'pqc_algorithm',
        'overview',
        'limitation',
        'installation_step',
        'testing',
        'image',
        'firebase_updated_at',
    ];

    protected $casts = [
        'open_source'       => 'boolean',
        'show'              => 'boolean',
        'pqc_algorithm'     => 'array',
        'installation_step' => 'array',
        'testing'           => 'array',
        'image'             => 'array',
        'firebase_updated_at' => 'datetime',
    ];

    /**
     * Scope: only visible libraries (show = true)
     */
    public function scopeVisible($query)
    {
        return $query->where('show', true);
    }

    /**
     * Scope: filter by PQC algorithm name (e.g. 'Kyber')
     */
    public function scopeHasAlgorithm($query, string $algorithm)
    {
        return $query->whereJsonContains('pqc_algorithm', $algorithm);
    }

    /**
     * Scope: filter by language (partial match)
     */
    public function scopeHasLanguage($query, string $language)
    {
        return $query->where('language', 'like', "%{$language}%");
    }

    /**
     * Check if this library supports PQC
     */
    public function supportsPqc(): bool
    {
        if (empty($this->pqc_algorithm)) return false;
        foreach ($this->pqc_algorithm as $alg) {
            if (stripos($alg, 'pqc unsupported') !== false) return false;
        }
        return true;
    }

    /**
     * Get pqc_algorithm as a normalized array of trimmed strings
     */
    public function getPqcAlgorithmsAttribute(): array
    {
        if (empty($this->pqc_algorithm)) return [];
        return array_map('trim', (array) $this->pqc_algorithm);
    }
}
