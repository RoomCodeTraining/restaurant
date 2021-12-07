<?php

namespace App\Models;

use App\Models\Menu;
use App\Support\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dish extends Model
{
    use HasFactory;

    use HasImage;

    protected $fillable = [
        'name', 'description', 'dish_type_id', 'image'
    ];

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

    public function dishMenu(){
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
        switch ($this->dish_type_id) {
            case DishType::STARTER:
                return asset('images/entree1.png');
            case DishType::MAIN:
                return asset('images/plat1.png');
            case DishType::DESSERT:
                return asset('images/dessert1.png');
            default:
            return asset('images/plat1.png');
        };
    }

    public function canBeOrdered(): bool
    {
        return $this->dishType->is_orderable;
    }
}
