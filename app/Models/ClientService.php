<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientService extends Model
{
    use HasFactory;

    // evito che laravel mi metta al plurale questa tabella
    protected $table = 'client_service';


    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function client()
    {
        return $this->belongsTo(Client :: class);
    }

    public function service()
    {
        return $this->belongsTo(Service :: class);
    }
}
