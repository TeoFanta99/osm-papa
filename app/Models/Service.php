<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\ServiceSold;
use App\Models\Commission;

class Service extends Model
{
    use HasFactory;

    public function clients()
    {
        return $this -> belongsToMany(Client :: class);
    }

    public function servicesSold()
    {
        return $this -> hasMany(ServiceSold :: class);
    }


}
