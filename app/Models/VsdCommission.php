<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VsdCommission extends Model
{
    use HasFactory;

    protected $fillable = ['services_per_installment_id', 'value'];

    public function servicePerInstallment()
    {
        return $this -> belongsTo(ServicePerInstallment :: class);
    }

    public function consultant()
    {
        return $this -> belongsTo(Consultant :: class);
    }
}
