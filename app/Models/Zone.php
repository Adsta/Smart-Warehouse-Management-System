<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    protected $fillable = ['name', 'code', 'type', 'description'];

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function isColdStorage(): bool
    {
        return $this->type === 'cold_storage';
    }
}
