<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Consultant;
use App\Models\Service;
use App\Models\Invoice;

class Client extends Model
{
    use HasFactory;

    public function consultant() 
    {
        return $this -> belongsTo(Consultant :: class);
    }

    public function invoices()
    {
        return $this -> hasMany(Invoice :: class);
    }
}
