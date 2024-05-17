<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount', 
        'expire_date',
        'paid',
    ];

    public function invoice()
    {
        return $this -> belongsTo(Invoice :: class);
    }    
}
