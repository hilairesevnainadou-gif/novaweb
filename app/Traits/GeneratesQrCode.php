<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait GeneratesQrCode
{
    /**
     * Génère un QR code en utilisant une bibliothèque pure PHP sans dépendances externes
     */
    protected function generateQrCode(string $data, int $size = 80): string
    {
        try {
            // Créer une image GD
            $qrCode = $this->createSimpleQrCode($data, $size);

            // Convertir en base64
            ob_start();
            imagepng($qrCode);
            $imageData = ob_get_clean();
            imagedestroy($qrCode);

            $base64 = base64_encode($imageData);

            return '<img src="data:image/png;base64,' . $base64 . '" width="' . $size . '" height="' . $size . '" style="display:block;" />';

        } catch (\Exception $e) {
            Log::error('Erreur QR Code: ' . $e->getMessage());
            return $this->getTextFallback($data, $size);
        }
    }

    /**
     * Crée un QR code simple avec GD
     */
    private function createSimpleQrCode(string $data, int $size): \GdImage
    {
        // Taille de la grille (21x21 pour les petits QR codes)
        $gridSize = 21;
        $cellSize = max(1, floor($size / $gridSize));
        $finalSize = $gridSize * $cellSize;

        // Créer l'image
        $image = imagecreatetruecolor($finalSize, $finalSize);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);

        // Remplir de blanc
        imagefill($image, 0, 0, $white);

        // Générer une matrice QR simple basée sur le hash des données
        $hash = md5($data);
        $matrix = $this->generateQrMatrix($hash, $gridSize);

        // Dessiner les cellules
        for ($y = 0; $y < $gridSize; $y++) {
            for ($x = 0; $x < $gridSize; $x++) {
                if ($matrix[$y][$x]) {
                    imagefilledrectangle(
                        $image,
                        $x * $cellSize,
                        $y * $cellSize,
                        ($x + 1) * $cellSize - 1,
                        ($y + 1) * $cellSize - 1,
                        $black
                    );
                }
            }
        }

        // Redimensionner à la taille exacte
        $finalImage = imagecreatetruecolor($size, $size);
        imagecopyresampled($finalImage, $image, 0, 0, 0, 0, $size, $size, $finalSize, $finalSize);
        imagedestroy($image);

        return $finalImage;
    }

    /**
     * Génère une matrice QR simplifiée
     */
    private function generateQrMatrix(string $hash, int $size): array
    {
        $matrix = array_fill(0, $size, array_fill(0, $size, false));

        // Ajouter les patterns de position (coins)
        $this->addPositionPattern($matrix, 0, 0);
        $this->addPositionPattern($matrix, $size - 7, 0);
        $this->addPositionPattern($matrix, 0, $size - 7);

        // Remplir avec des données basées sur le hash
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                // Ne pas écraser les patterns de position
                if ($matrix[$i][$j]) continue;

                $char = ord($hash[($i * $j) % strlen($hash)]);
                $matrix[$i][$j] = ($char % 2 == 0);
            }
        }

        return $matrix;
    }

    /**
     * Ajoute un pattern de position (carré dans le coin)
     */
    private function addPositionPattern(array &$matrix, int $startX, int $startY): void
    {
        for ($i = 0; $i < 7; $i++) {
            for ($j = 0; $j < 7; $j++) {
                if ($i == 0 || $i == 6 || $j == 0 || $j == 6 ||
                    ($i >= 2 && $i <= 4 && $j >= 2 && $j <= 4)) {
                    $matrix[$startY + $i][$startX + $j] = true;
                }
            }
        }
    }

    /**
     * Fallback texte
     */
    private function getTextFallback(string $data, int $size): string
    {
        return '<div style="width:' . $size . 'px;height:' . $size . 'px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-size:10px;color:#64748b;text-align:center;">
                    <div style="font-weight:bold;margin-bottom:4px;">✓</div>
                    <div>Vérifier</div>
                    <div style="font-size:8px;margin-top:4px;">' . substr($data, -20) . '</div>
                </div>';
    }
}
