<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageHallCompatibility extends Model
{
    use HasFactory;

    protected $table = 'package_hall_compatibility';

    protected $fillable = [
        'package_id',
        'hall_id',
        'compatibility_score',
        'special_pricing',
        'special_features',
        'is_recommended',
        'compatibility_notes'
    ];

    protected $casts = [
        'special_features' => 'array',
        'special_pricing' => 'decimal:2',
        'compatibility_score' => 'integer',
        'is_recommended' => 'boolean'
    ];

    /**
     * Get the package that owns the compatibility record
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the hall that owns the compatibility record
     */
    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    /**
     * Scope for recommended combinations
     */
    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    /**
     * Scope for high compatibility scores
     */
    public function scopeHighCompatibility($query, $minScore = 80)
    {
        return $query->where('compatibility_score', '>=', $minScore);
    }

    /**
     * Get compatibility rating as text
     */
    public function getCompatibilityRatingAttribute()
    {
        if ($this->compatibility_score >= 90) {
            return 'Excellent';
        } elseif ($this->compatibility_score >= 80) {
            return 'Very Good';
        } elseif ($this->compatibility_score >= 70) {
            return 'Good';
        } elseif ($this->compatibility_score >= 60) {
            return 'Fair';
        } else {
            return 'Poor';
        }
    }

    /**
     * Get effective pricing (special pricing if available, otherwise package price)
     */
    public function getEffectivePricingAttribute()
    {
        return $this->special_pricing ?? $this->package->price;
    }

    /**
     * Check if this combination has special features
     */
    public function hasSpecialFeatures()
    {
        return !empty($this->special_features);
    }

    /**
     * Get combined features (package features + special features)
     */
    public function getCombinedFeaturesAttribute()
    {
        $packageFeatures = $this->package->features ?? [];
        $specialFeatures = $this->special_features ?? [];
        
        return array_merge($packageFeatures, $specialFeatures);
    }
}