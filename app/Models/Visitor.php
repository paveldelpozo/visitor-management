<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'surname', 'company',
        'identity_id', 'phone', 'email',
        'headphones',
    ];

    public function scopeSearch($query, string $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->where('name', 'like', "%$value%")
                ->orWhere('surname', 'like', "%$value%")
                ->orWhere('company', 'like', "%$value%")
                ->orWhere('identity_id', 'like', "%$value%")
                ->orWhere('phone', 'like', "%$value%")
                ->orWhere('email', 'like', "%$value%");
        });
    }

    public function scopeSearchName($query, string $value)
    {
        return $query->where('name', 'like', "%$value%");
    }

    public function scopeSearchSurname($query, string $value)
    {
        return $query->where('surname', 'like', "%$value%");
    }

    public function scopeSearchCompany($query, string $value)
    {
        return $query->where('company', 'like', "%$value%");
    }

    public function scopeSearchIdentityId($query, string $value)
    {
        return $query->where('identity_id', 'like', "%$value%");
    }

    public function scopeSearchPhone($query, string $value)
    {
        return $query->where('phone', 'like', "%$value%");
    }

    public function scopeSearchEmail($query, string $value)
    {
        return $query->where('email', 'like', "%$value%");
    }

    public function logs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VisitorLog::class);
    }
}
