<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePerInstallment extends Model
{
    // protected $fillable = [
    //     'installment_id',
    //     'service_id',
    // ];

    use HasFactory;

    protected $table = 'services_per_installment';

    public function serviceSold() 
    {
        return $this -> belongsTo(ServiceSold :: class);
    }

    public function installment() 
    {
        return $this -> belongsTo(Installment :: class);
    }

    public function vssCommission()
    {
        return $this -> hasOne(VssCommission :: class);
    }

    public function vsdCommission()
    {
        return $this -> hasOne(VsdCommission :: class);
    }

}
