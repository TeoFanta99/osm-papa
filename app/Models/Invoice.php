<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Installment;
use App\Models\ServiceSold;

class Invoice extends Model
{
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function servicesSold()
    {
        return $this->hasMany(ServiceSold::class);
    }
}
