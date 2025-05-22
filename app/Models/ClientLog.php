<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientLog extends Model
{
    protected $fillable = ['user_id', 'message', 'stack', 'info', 'url', 'ip'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}