<?php

namespace App\Controllers;

use App\Models\SettingsModel;

class SettingsController extends BaseController
{
    protected $settings;

    public function __construct()
    {
        $this->settings = new SettingsModel();
        helper(['form', 'url']);
    }

    private function userId(): int
    {
        return (int) session()->get('user_id');
    }

    public function index()
    {
        $settings = $this->settings
            ->where('user_id', $this->userId())
            ->first();

        return view('settings/index', [
            'title' => 'Company Settings',
            'settings' => $settings ?? [],
        ]);
    }

    public function save()
    {
        $userId = $this->userId();

        $rules = [
            'company_name' => 'permit_empty|max_length[150]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'phone' => 'permit_empty|max_length[20]',
            'tax_id' => 'permit_empty|max_length[50]',
            'invoice_prefix' => 'required|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = [
            'company_name' => trim((string) $this->request->getPost('company_name')),
            'address' => trim((string) $this->request->getPost('address')),
            'phone' => trim((string) $this->request->getPost('phone')),
            'email' => trim((string) $this->request->getPost('email')),
            'tax_id' => trim((string) $this->request->getPost('tax_id')),
            'bank_details' => trim((string) $this->request->getPost('bank_details')),
            'invoice_prefix' => strtoupper(trim((string) $this->request->getPost('invoice_prefix'))),
            'footer_note' => trim((string) $this->request->getPost('footer_note')),
        ];

        $existing = $this->settings
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $this->settings->update($existing['id'], $data);
        } else {
            $data['user_id'] = $userId;
            $this->settings->insert($data);
        }

        return redirect()->to(site_url('settings'))
            ->with('success', 'Settings saved successfully.');
    }
}
