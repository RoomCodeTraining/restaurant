<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public function scopeIsMaster($query)
    {
        return $query->whereDoesntHave('parent')->whereHas('children');
    }

    public function scopeIsParent($query)
    {
        return $query->whereHas('children');
    }

    public function scopeIsChild($query)
    {
        return $query->whereHas('parent');
    }

    public function scopeSingular($query)
    {
        return $query->whereNull('parent_id')->whereDoesntHave('children');
    }

    public function getCategorizedPermissions()
    {
        return static::with('children')->get();
    }

    public function getUncategorizedPermissions()
    {
        return static::singular()->get();
    }

    public function parent()
    {
        return $this->belongsTo(__CLASS__, 'parent_id')->with('parent');
    }

    public function children()
    {
        return $this->hasMany(__CLASS__, 'parent_id')->with('children');
    }
}
