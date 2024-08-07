<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pothole extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function image(): Attribute
    {
        return new Attribute(get: fn ($attr) => $attr ? Storage::disk('public')->url($attr) : null);
    }
}
