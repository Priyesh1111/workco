<?php

namespace App\Controllers;

use App\Models\InvoicesModel;

class DashboardController extends BaseController
{
    private function userId(): int
    {
        return (int) session()->get('user_id');
    }

    public function index()
    {
        $model = new InvoicesModel();
        $today = date('Y-m-d');

        return view('dashboard/index', [
            'title'    => 'Dashboard',
            'stats'    => $this->formatStats($model->dashboardStats($this->userId(), $today)),
            'invoices' => $model->latestWithCustomer($this->userId()),
            'today'    => $today,
        ]);
    }

    public function data()
    {
        $model = new InvoicesModel();
        $today = date('Y-m-d');

        return $this->response
            ->setHeader('Cache-Control', 'no-store')
            ->setJSON([
                'stats'      => $this->formatStats($model->dashboardStats($this->userId(), $today)),
                'rows_html'  => view('dashboard/_invoice_rows', [
                    'invoices' => $model->latestWithCustomer($this->userId()),
                    'today'    => $today,
                ]),
                'updated_at' => date('h:i:s A'),
            ]);
    }

    private function formatStats(array $stats): array
    {
        return [
            'total_invoices' => number_format($stats['total_invoices']),
            'paid_amount'    => '₹' . number_format($stats['paid_amount'], 2),
            'unpaid_amount'  => '₹' . number_format($stats['unpaid_amount'], 2),
            'overdue_count'  => number_format($stats['overdue_count']),
        ];
    }
}