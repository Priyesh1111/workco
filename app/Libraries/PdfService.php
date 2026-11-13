<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;
use RuntimeException;

class PdfService
{
    private const LOGO_MAX_WIDTH  = 190;
    private const LOGO_MAX_HEIGHT = 70;

    public function render(array $data): string
    {
        if (!class_exists(Dompdf::class)) {
            throw new RuntimeException('Dompdf is not installed. Run: composer require dompdf/dompdf');
        }

        $data['logo'] = $this->logoForPdf($data['settings']['logo_path'] ?? null);

        $html = view('invoices/pdf_template', $data);

        $options = new Options([
            'defaultFont'     => 'DejaVu Sans',
            'isRemoteEnabled' => false,
        ]);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $canvas = $dompdf->getCanvas();
        $font   = $dompdf->getFontMetrics()->getFont('DejaVu Sans', 'normal');
        $canvas->page_text(
            $canvas->get_width() - 95,
            $canvas->get_height() - 32,
            'Page {PAGE_NUM} of {PAGE_COUNT}',
            $font,
            8,
            [0.45, 0.45, 0.45]
        );

        return $dompdf->output();
    }

    public function filename(array $invoice): string
    {
        $name = preg_replace('/[^A-Za-z0-9._-]/', '-', (string) $invoice['invoice_no']);

        return $name . '.pdf';
    }

    private function logoForPdf(?string $logoPath): ?array
    {
        if (empty($logoPath)) {
            return null;
        }

        $base = realpath(FCPATH . 'uploads/logos');
        $full = realpath(FCPATH . ltrim($logoPath, '/\\'));

        if ($base === false || $full === false || strpos($full, $base . DIRECTORY_SEPARATOR) !== 0 || !is_file($full)) {
            log_message('warning', 'Invoice PDF: logo file not found: ' . $logoPath);

            return null;
        }

        $bytes = file_get_contents($full);
        $info  = $bytes === false ? false : @getimagesizefromstring($bytes);

        if ($info === false) {
            return null;
        }

        $mime = $info['mime'];

        if ($mime === 'image/webp') {
            $image = function_exists('imagecreatefromstring') ? @imagecreatefromstring($bytes) : false;

            if ($image === false) {
                return null;
            }

            imagealphablending($image, false);
            imagesavealpha($image, true);
            ob_start();
            imagepng($image);
            $bytes = (string) ob_get_clean();
            imagedestroy($image);
            $mime = 'image/png';
        } elseif (!in_array($mime, ['image/png', 'image/jpeg'], true)) {
            return null;
        }

        $scale = min(self::LOGO_MAX_WIDTH / $info[0], self::LOGO_MAX_HEIGHT / $info[1], 1);

        return [
            'src'    => 'data:' . $mime . ';base64,' . base64_encode($bytes),
            'width'  => max(1, (int) round($info[0] * $scale)),
            'height' => max(1, (int) round($info[1] * $scale)),
        ];
    }
}