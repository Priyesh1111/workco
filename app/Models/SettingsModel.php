<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'company_name',
        'address',
        'phone',
        'email',
        'tax_id',
        'logo_path',
        'bank_details',
        'invoice_prefix',
        'footer_note',
    ];
}
