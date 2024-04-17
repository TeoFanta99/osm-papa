<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Consultant;
use App\Models\Service;

class Client extends Model
{
    use HasFactory;

    public function consultant() 
    {
        return $this -> belongsTo(Consultant :: class);
    }

    public function services()
    {
        return $this -> belongsToMany(Service :: class);
    }

    public function clientServices()
    {
        return $this -> hasMany(ClientService :: class);
    }
}
