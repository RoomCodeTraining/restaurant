<?php

namespace App\Models;

use App\Support\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    use HasImage;

    protected $fillable = [
        'name', 'description', 'dish_type_id', 'image_path',
    ];

    public function getPositionAttribute()
    {
        switch ($this->dish_type_id) {
            case DishType::STARTER:
                return 1;
            case DishType::MAIN:
                return 2;
            case DishType::DESSERT:
                return 3;
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
        return $query->where('dish_type_id', DishType::STARTER);
    }

    public function dishMenu()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function scopeMain($query)
    {
        return $query->where('dish_type_id', DishType::MAIN);
    }

    public function scopeDessert($query)
    {
        return $query->where('dish_type_id', DishType::DESSERT);
    }

    public function dishType()
    {
        return $this->belongsTo(DishType::class);
    }

    protected function defaultImageUrl()
    {
        $img = asset("storage/".$this->attributes['image_path']); //Image chargée lors de la creation du plat

        
        switch ($this->dish_type_id) {
            case DishType::STARTER:
                return $this->attributes['image_path'] ? $img : asset('images/entree1.png');
            case DishType::MAIN:
                return $this->attributes['image_path'] ? $img : asset('images/plat1.png');
            case DishType::DESSERT:
                return $this->attributes['image_path'] ? $img : asset('images/dessert1.png');
            default:
                return $this->attributes['image_path'] ? $img : asset('images/plat1.png');
        };
    }

    public function canBeOrdered(): bool
    {
        return $this->dishType->is_orderable;
    }
}
