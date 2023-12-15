<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessCardHistory extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'attached_at' => 'datetime',
        'detached_at' => 'datetime',
    ];

    /**
     * Get the user that owns the AccessCardHistory
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Get the accessCard that owns the AccessCardHistory
     * @return BelongsTo
     */
    public function accessCard()
    {
        return $this->belongsTo(AccessCard::class);
    }
}