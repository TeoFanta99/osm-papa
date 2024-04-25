<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Installment;

class InstallmentInfo extends Model
{
    use HasFactory;

    protected $table = 'installments_info';

    public function installment()
    {
        return $this -> belongsTo(Installment :: class);
    }
}
