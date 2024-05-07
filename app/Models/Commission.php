<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Installment;
use App\Models\Service;

class Commission extends Model
{
    use HasFactory;

    protected $table = 'commissions';

    public function installment()
    {
        return $this -> belongsTo(Installment :: class);
    }

    public function services()
    {
        return $this -> belongsToMany(Service :: class);
    }
}
