<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientService;

class Installment extends Model
{
    use HasFactory;

    public function client_service()
    {
        return $this -> belongsTo(ClientService :: class);
    }
    
}
