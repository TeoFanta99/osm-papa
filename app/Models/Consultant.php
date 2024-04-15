<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Level;
use App\Models\Client;

class Consultant extends Model
{
    use HasFactory;

    public function user()
    {
        return $this -> belongsTo(User :: class);
    }

    public function level()
    {
        return $this -> belongsTo(Level :: class);
    }

    public function clients()
    {
        return $this -> hasMany(Client :: class);
    }
}
