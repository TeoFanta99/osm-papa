<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\Service;

class ServiceSold extends Model
{
    use HasFactory;

    protected $table = 'services_sold';

    public function invoice()
    {
        return $this -> belongsTo(Invoice :: class);
    }

    public function service()
    {
        return $this -> belongsTo(Service :: class);
    }
}
