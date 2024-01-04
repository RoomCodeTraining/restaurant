<?php

namespace App\Models;

use App\Support\HasImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    use HasImage;


    protected $fillable = [
        'name', 'description', 'dish_type_id', 'image_path',
    ];

    protected $casts = [
        'image_path' => 'string'
    ];



    public function getPositionAttribute()
    {
        switch ($this->dish_type_id) {
            case \App\Enums\DishType::SIDE:
                return 2;
            case \App\Enums\DishType::MAIN:
                return 2;
            default:
                return 1;
        };
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(
            fn ($query) => $query->where('name', 'like', '%'.$term.'%')
        );
    }

    public function scopeStarter($query)
    {
        return $query->where('dish_type_id', \App\Enums\DishType::SIDE);
    }

    public function dishMenu()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function scopeMain($query)
    {
        return $query->where('dish_type_id', \App\Enums\DishType::MAIN);
    }

    public function dishType()
    {
        return $this->belongsTo(DishType::class);
    }

    protected function defaultImageUrl()
    {
        $img = asset("storage/".$this->attributes['image_path']);
        switch ($this->dish_type_id) {
            case \App\Enums\DishType::MAIN:
                return $this->attributes['image_path'] ? $img : asset('images/plat1.png');
            default:
                return $this->attributes['image_path'] ? $img : asset('images/plat1.png');
        };
    }

    public function image() : Attribute
    {
        return new Attribute(
            fn () => $this->defaultImageUrl(),
        );
    }

    public function canBeOrdered(): bool
    {
        return $this->dishType->is_orderable;
    }


    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }
}