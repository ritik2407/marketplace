<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Listing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'subcategory_id',
        'country_id',
        'state_id',
        'city_id',
        'area_id',
        'type',
        'title',
        'slug',
        'description',
        'price',
        'is_negotiable',
        'condition',
        'status',
        'is_featured',
        'phone',
        'whatsapp',
        'email',
        'views_count',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_negotiable' => 'boolean',
            'is_featured' => 'boolean',
            'views_count' => 'integer',
        ];
    }

    /**
     * Boot function for slug generation.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($listing) {
            if (empty($listing->slug)) {
                $baseSlug = Str::slug($listing->title);
                $uniqueSlug = $baseSlug.'-'.Str::random(6);
                $listing->slug = $uniqueSlug;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class)->orderBy('is_primary', 'desc')->orderBy('order', 'asc');
    }

    public function primaryImage(): BelongsTo
    {
        return $this->belongsTo(ListingImage::class, 'id', 'listing_id')->where('is_primary', true);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Scope to active listings.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to featured listings.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Dynamic filtering scope supporting multiple simultaneous filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['type'])) {
            if (is_array($filters['type'])) {
                $query->whereIn('type', $filters['type']);
            } else {
                $query->where('type', $filters['type']);
            }
        }

        if (! empty($filters['category_id'])) {
            if (is_array($filters['category_id'])) {
                $query->whereIn('category_id', array_filter($filters['category_id']));
            } else {
                $query->where('category_id', $filters['category_id']);
            }
        }

        if (! empty($filters['category'])) {
            $categorySlug = $filters['category'];
            if (is_array($categorySlug)) {
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->whereIn('slug', array_filter($categorySlug));
                });
            } else {
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            }
        }

        if (! empty($filters['subcategory_id'])) {
            if (is_array($filters['subcategory_id'])) {
                $query->whereIn('subcategory_id', array_filter($filters['subcategory_id']));
            } else {
                $query->where('subcategory_id', $filters['subcategory_id']);
            }
        }

        if (! empty($filters['subcategory'])) {
            $subcategorySlug = $filters['subcategory'];
            if (is_array($subcategorySlug)) {
                $query->whereHas('subcategory', function ($q) use ($subcategorySlug) {
                    $q->whereIn('slug', array_filter($subcategorySlug));
                });
            } else {
                $query->whereHas('subcategory', function ($q) use ($subcategorySlug) {
                    $q->where('slug', $subcategorySlug);
                });
            }
        }

        if (! empty($filters['state_id'])) {
            if (is_array($filters['state_id'])) {
                $query->whereIn('state_id', array_filter($filters['state_id']));
            } else {
                $query->where('state_id', $filters['state_id']);
            }
        }

        if (! empty($filters['city_id'])) {
            if (is_array($filters['city_id'])) {
                $query->whereIn('city_id', array_filter($filters['city_id']));
            } else {
                $query->where('city_id', $filters['city_id']);
            }
        }

        if (! empty($filters['city'])) {
            $citySlug = $filters['city'];
            if (is_array($citySlug)) {
                $query->whereHas('city', function ($q) use ($citySlug) {
                    $q->whereIn('slug', array_filter($citySlug));
                });
            } else {
                $query->whereHas('city', function ($q) use ($citySlug) {
                    $q->where('slug', $citySlug);
                });
            }
        }

        if (! empty($filters['area_id'])) {
            if (is_array($filters['area_id'])) {
                $query->whereIn('area_id', array_filter($filters['area_id']));
            } else {
                $query->where('area_id', $filters['area_id']);
            }
        }

        if (isset($filters['min_price']) && $filters['min_price'] !== '' && $filters['min_price'] !== null) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }

        if (isset($filters['max_price']) && $filters['max_price'] !== '' && $filters['max_price'] !== null) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        if (! empty($filters['condition'])) {
            if (is_array($filters['condition'])) {
                $conditions = array_values(array_filter($filters['condition']));
                if (! empty($conditions)) {
                    $query->whereIn('condition', $conditions);
                }
            } else {
                $query->where('condition', $filters['condition']);
            }
        }

        if (isset($filters['is_negotiable']) && $filters['is_negotiable'] !== '' && $filters['is_negotiable'] !== null) {
            $isNegotiable = filter_var($filters['is_negotiable'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($isNegotiable !== null) {
                $query->where('is_negotiable', $isNegotiable);
            }
        }

        if (isset($filters['is_featured']) && $filters['is_featured'] !== '' && $filters['is_featured'] !== null) {
            $isFeatured = filter_var($filters['is_featured'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($isFeatured !== null) {
                $query->where('is_featured', $isFeatured);
            }
        }

        if (isset($filters['with_photos']) && $filters['with_photos'] !== '' && $filters['with_photos'] !== null) {
            $withPhotos = filter_var($filters['with_photos'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($withPhotos) {
                $query->has('images');
            }
        }

        if (! empty($filters['posted_within'])) {
            match ($filters['posted_within']) {
                'today', '24h' => $query->where('created_at', '>=', now()->subDay()),
                'week', '7d' => $query->where('created_at', '>=', now()->subDays(7)),
                'month', '30d' => $query->where('created_at', '>=', now()->subDays(30)),
                default => null,
            };
        }

        // Sorting
        $sort = $filters['sort'] ?? 'newest';

        return match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderBy('views_count', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };
    }

    /**
     * Get primary image URL with fallback.
     */
    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();

        if ($primary) {
            if (str_starts_with($primary->image_path, 'http')) {
                return $primary->image_path;
            }

            return asset('storage/'.$primary->image_path);
        }

        return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';
    }

    /**
     * Formatted price string (e.g. ₹ 25,000).
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₹ '.number_format((float) $this->price, 0);
    }

    /**
     * Get location hierarchy string.
     */
    public function getLocationStringAttribute(): string
    {
        $parts = [];
        if ($this->area) {
            $parts[] = $this->area->name;
        }
        if ($this->city) {
            $parts[] = $this->city->name;
        } elseif ($this->state) {
            $parts[] = $this->state->name;
        }

        return ! empty($parts) ? implode(', ', $parts) : 'India';
    }
}
