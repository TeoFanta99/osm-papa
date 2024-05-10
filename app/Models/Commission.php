<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Installment;
use App\Models\Service;
use App\Models\Consultant;

class Commission extends Model
{
    use HasFactory;

    protected $table = 'commissions';

    public function installment()
    {
        return $this -> belongsTo(Installment :: class);
    }


    public function soldBy() {
        return $this->belongsTo(Consultant::class, 'sold_by');
    }
    
    public function deliveredBy() {
        return $this->belongsTo(Consultant::class, 'delivered_by');
    }
    
    public function serviceId() {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
