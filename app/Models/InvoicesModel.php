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

        public function dashboardStats(int $userId, string $today): array
    {
        $open = ['paid', 'cancelled'];

        $total = $this->db->table($this->table)
            ->where('user_id', $userId)
            ->countAllResults();

        $paid = $this->db->table($this->table)
            ->selectSum('grand_total', 'amount')
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->get()->getRowArray();

        $unpaid = $this->db->table($this->table)
            ->selectSum('grand_total', 'amount')
            ->where('user_id', $userId)
            ->whereNotIn('status', $open)
            ->get()->getRowArray();

        $overdue = $this->db->table($this->table)
            ->where('user_id', $userId)
            ->whereNotIn('status', $open)
            ->where('due_date <', $today)
            ->countAllResults();

        return [
            'total_invoices' => (int) $total,
            'paid_amount'    => (float) ($paid['amount'] ?? 0),
            'unpaid_amount'  => (float) ($unpaid['amount'] ?? 0),
            'overdue_count'  => (int) $overdue,
        ];
    }

    public function latestWithCustomer(int $userId): array
    {
        return $this->db->table($this->table)
            ->select('invoices.*, customers.name AS customer_name')
            ->join('customers', 'customers.id = invoices.customer_id', 'left')
            ->where('invoices.user_id', $userId)
            ->orderBy('invoices.id', 'DESC')
            ->get()->getResultArray();
    }
}
