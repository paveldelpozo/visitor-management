<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadphoneStock extends Model
{
    use HasFactory;

    protected $fillable = ['total'];

    public static function getTotal(): int
    {
        return static::first()->total ?? 0;
    }

    public static function getAvailable(): int
    {
        $total = static::getTotal();
        $used = Visitor::sum('headphones');
        return max(0, $total - $used);
    }
}
