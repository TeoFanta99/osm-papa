<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $table = 'commissions';

    // public function installment()
    // {
    //     return $this -> belongsTo(Installment :: class);
    // }

    // public function soldBy() {
    //     return $this->belongsTo(Consultant::class, 'sold_by');
    // }
    
    // public function deliveredBy() {
    //     return $this->belongsTo(Consultant::class, 'delivered_by');
    // }
    
    // public function serviceId() {
    //     return $this->belongsTo(Service::class, 'service_id');
    // }

    public function consultant() 
    {
        return $this -> belongsTo(Consultant :: class);
    }

    public function commissionType()
    {
        return $this -> belognsTo(CommissionType :: class);
    }

}
