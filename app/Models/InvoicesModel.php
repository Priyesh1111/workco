<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoicesModel extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'customer_id',
        'invoice_no',
        'issue_date',
        'due_date',
        'status',
        'subtotal',
        'discount',
        'tax_total',
        'grand_total',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
