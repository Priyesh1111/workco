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

    private function findOwnedSetting(int $id): array
    {
        $setting = $this->settings
            ->where('user_id', $this->userId())
            ->find($id);

        if (!$setting) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Setting not found.'
            );
        }

        return $setting;
    }

    private function validationRules(): array
    {
        return [
            'company_name' => 'permit_empty|max_length[150]',
            'address' => 'permit_empty',
            'phone' => 'permit_empty|max_length[20]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'tax_id' => 'permit_empty|max_length[50]',
            'bank_details' => 'permit_empty',
            'invoice_prefix' => 'required|max_length[20]',
            'footer_note' => 'permit_empty',
            'logo' => [
                'label' => 'Company Logo',
                'rules' => 'permit_empty|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png,image/webp]|max_size[logo,1024]',
                'errors' => [
                    'is_image' => 'The uploaded file must be a valid image.',
                    'mime_in' => 'Logo must be a PNG, JPG, or WEBP file.',
                    'max_size' => 'Logo size must not exceed 1 MB.',
                ],
            ],
        ];
    }

    private function collectPostData(): array
    {
        return [
            'company_name' => trim((string) $this->request->getPost('company_name')),
            'address' => trim((string) $this->request->getPost('address')),
            'phone' => trim((string) $this->request->getPost('phone')),
            'email' => trim((string) $this->request->getPost('email')),
            'tax_id' => trim((string) $this->request->getPost('tax_id')),
            'bank_details' => trim((string) $this->request->getPost('bank_details')),
            'invoice_prefix' => strtoupper(trim((string) $this->request->getPost('invoice_prefix'))),
            'footer_note' => trim((string) $this->request->getPost('footer_note')),
        ];
    }

    private function handleLogoUpload(?string $oldLogoPath = null): ?string
    {
        $file = $this->request->getFile('logo');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $oldLogoPath;
        }

        $uploadDir = FCPATH . 'uploads/logos';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadDir, $newName);

        if (!empty($oldLogoPath)) {
            $fullOldPath = FCPATH . ltrim($oldLogoPath, '/');
            if (is_file($fullOldPath)) {
                @unlink($fullOldPath);
            }
        }

        return 'uploads/logos/' . $newName;
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

        if (!$this->validate($this->validationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $existing = $this->settings
            ->where('user_id', $userId)
            ->first();

        $data = $this->collectPostData();
        $data['logo_path'] = $this->handleLogoUpload($existing['logo_path'] ?? null);

        if ($existing) {
            $this->settings->update($existing['id'], $data);
        } else {
            $data['user_id'] = $userId;
            $this->settings->insert($data);
        }

        return redirect()->to(site_url('settings'))
            ->with('success', 'Settings saved successfully.');
    }

    public function edit(int $id = 0)
    {
        if ($id === 0) {
            $existing = $this->settings
                ->where('user_id', $this->userId())
                ->first();

            if ($existing) {
                return redirect()->to(site_url('settings/edit/' . $existing['id']));
            }

            return view('settings/edit', [
                'title' => 'Setup Company Settings',
                'setting' => [],
            ]);
        }

        return view('settings/edit', [
            'title' => 'Edit Company Settings',
            'setting' => $this->findOwnedSetting($id),
        ]);
    }

    public function update(int $id)
    {
        $existing = $this->findOwnedSetting($id);

        if (!$this->validate($this->validationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = $this->collectPostData();
        $data['logo_path'] = $this->handleLogoUpload($existing['logo_path'] ?? null);

        $this->settings->update($id, $data);

        return redirect()->to(site_url('settings'))
            ->with('success', 'Settings updated successfully.');
    }
}