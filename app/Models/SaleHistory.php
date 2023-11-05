<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleHistory extends Model
{
    protected $table            = 'sale_history';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];


}
