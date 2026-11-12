<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceItemsModel extends Model
{
    protected $table = 'invoice_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'invoice_id',
        'product_id',
        'description',
        'qty',
        'unit_price',
        'tax_percent',
        'line_total',
    ];
}
