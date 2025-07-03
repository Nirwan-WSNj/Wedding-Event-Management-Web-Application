<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CateringItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'menu_id',
        'name',
        'description',
        'category',
        'dietary_info'
    ];

    protected $casts = [
        'dietary_info' => 'array'
    ];

    // Common food categories
    const CATEGORY_APPETIZER = 'appetizer';
    const CATEGORY_SOUP = 'soup';
    const CATEGORY_SALAD = 'salad';
    const CATEGORY_MAIN_COURSE = 'main_course';
    const CATEGORY_DESSERT = 'dessert';
    const CATEGORY_BEVERAGE = 'beverage';
    const CATEGORY_SPECIAL = 'special';

    // Common dietary types
    const DIETARY_VEGETARIAN = 'vegetarian';
    const DIETARY_VEGAN = 'vegan';
    const DIETARY_GLUTEN_FREE = 'gluten_free';
    const DIETARY_DAIRY_FREE = 'dairy_free';
    const DIETARY_NUT_FREE = 'nut_free';
    const DIETARY_HALAL = 'halal';
    const DIETARY_KOSHER = 'kosher';

    public function menu(): BelongsTo
    {
        return $this->belongsTo(CateringMenu::class, 'menu_id');
    }

    // Scopes
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeDietary($query, $dietary)
    {
        return $query->whereJsonContains('dietary_info', $dietary);
    }

    // Methods
    public static function getCategories(): array
    {
        return [
            self::CATEGORY_APPETIZER => 'Appetizers',
            self::CATEGORY_SOUP => 'Soups',
            self::CATEGORY_SALAD => 'Salads',
            self::CATEGORY_MAIN_COURSE => 'Main Courses',
            self::CATEGORY_DESSERT => 'Desserts',
            self::CATEGORY_BEVERAGE => 'Beverages',
            self::CATEGORY_SPECIAL => 'Special Items'
        ];
    }

    public static function getDietaryTypes(): array
    {
        return [
            self::DIETARY_VEGETARIAN => 'Vegetarian',
            self::DIETARY_VEGAN => 'Vegan',
            self::DIETARY_GLUTEN_FREE => 'Gluten Free',
            self::DIETARY_DAIRY_FREE => 'Dairy Free',
            self::DIETARY_NUT_FREE => 'Nut Free',
            self::DIETARY_HALAL => 'Halal',
            self::DIETARY_KOSHER => 'Kosher'
        ];
    }

    public function hasDietaryRestriction(string $restriction): bool
    {
        return in_array($restriction, $this->dietary_info ?? []);
    }

    public function getDietaryLabels(): array
    {
        $labels = [];
        foreach ($this->dietary_info ?? [] as $dietary) {
            $labels[] = self::getDietaryTypes()[$dietary] ?? $dietary;
        }
        return $labels;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Validate category
            if (!array_key_exists($item->category, self::getCategories())) {
                throw new \InvalidArgumentException('Invalid category');
            }

            // Validate dietary info
            if ($item->dietary_info) {
                $validDietary = array_keys(self::getDietaryTypes());
                foreach ($item->dietary_info as $dietary) {
                    if (!in_array($dietary, $validDietary)) {
                        throw new \InvalidArgumentException('Invalid dietary restriction: ' . $dietary);
                    }
                }
            }
        });
    }
}