<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;

class Service extends Model
{
    use HasFactory;

    public function clients()
    {
        return $this -> belongsToMany(Client :: class);
    }

    public function clientServices()
    {
        return $this -> hasMany(ClientService :: class);
    }
}
