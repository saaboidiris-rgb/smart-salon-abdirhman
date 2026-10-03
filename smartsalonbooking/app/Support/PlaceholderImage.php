<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Generates a simple colored placeholder JPEG using the GD extension, so
 * seeded demo data (services, employees, gallery, testimonials) has real
 * image files to point to instead of broken links - handy when running the
 * seeders somewhere without internet access to fetch stock photos.
 *
 * Once you're happy with the app, just upload real photos through the admin
 * panel and they'll replace these automatically.
 */
class PlaceholderImage
{
    protected const PALETTE = [
        [157, 27, 86],   // primary rose
        [110, 16, 57],   // primary dark
        [201, 162, 75],  // gold
        [224, 87, 126],  // accent
        [36, 25, 38],    // ink
    ];

    /**
     * Creates the image and stores it on the "public" disk, returning the
     * relative path (e.g. "services/xyz.jpg") ready to save on a model.
     */
    public static function make(string $folder, string $label, int $width = 640, int $height = 420): string
    {
        [$r, $g, $b] = self::PALETTE[crc32($label) % count(self::PALETTE)];

        $image = imagecreatetruecolor($width, $height);
        $bg = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, 0, 0, $width, $height, $bg);

        // Soft diagonal overlay stripes for a bit of texture.
        $stripe = imagecolorallocatealpha($image, 255, 255, 255, 110);
        for ($x = -$height; $x < $width; $x += 40) {
            imagefilledpolygon($image, [$x, 0, $x + 20, 0, $x + 20 - $height, $height, $x - $height, $height], $stripe);
        }

        $white = imagecolorallocate($image, 255, 255, 255);
        $text = mb_strtoupper($label);
        $fontWidth = imagefontwidth(5);
        $textX = max(10, (int) (($width - strlen($text) * $fontWidth) / 2));
        imagestring($image, 5, $textX, (int) ($height / 2) - 8, $text, $white);

        ob_start();
        imagejpeg($image, null, 82);
        $contents = ob_get_clean();
        imagedestroy($image);

        $path = $folder.'/'.\Illuminate\Support\Str::slug($label).'-'.substr(md5($label.random_int(1, 99999)), 0, 6).'.jpg';
        Storage::disk('public')->put($path, $contents);

        return $path;
    }
}
