<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }

    public function service()
    {
        return $this->belongsToMany(Service::class);
    }
}
