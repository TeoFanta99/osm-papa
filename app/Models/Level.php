<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Consultant;

class Level extends Model
{
    use HasFactory;

    public function consultants() 
    {
        return $this -> hasMany(Consultant :: class);
    }
}
