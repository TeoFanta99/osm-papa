<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\Commission;

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

    public function commissions()
    {
        return $this -> hasMany(Commission :: class);
    }
    
}
