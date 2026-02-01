<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    /**
     * Generate QR code and save as PNG file.
     * 
     * @param string $data The data to encode
     * @param string $filename The filename (without extension)
     * @return string The public URL to the QR code image
     */
    public function generateAndSave(string $data, string $filename): string
    {
        // Generate QR code as PNG
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($data);

        // Save to public/qrcodes directory
        $path = "qrcodes/{$filename}.png";
        Storage::disk('public')->put($path, $qrCode);

        // Return public URL
        return asset("storage/{$path}");
    }

    /**
     * Delete QR code file.
     */
    public function delete(string $filename): bool
    {
        $path = "qrcodes/{$filename}.png";
        return Storage::disk('public')->delete($path);
    }

    /**
     * Clear all QR codes.
     */
    public function clearAll(): int
    {
        $files = Storage::disk('public')->files('qrcodes');
        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }
        return count($files);
    }
}
