<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientService extends Model
{

    // evito che laravel mi metta al plurale questa tabella
    protected $table = 'client_service';
    
    use HasFactory;
}
