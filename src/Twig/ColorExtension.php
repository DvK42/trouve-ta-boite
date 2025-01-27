<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ColorExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('hex_to_rgba', [$this, 'convertHexToRgba']),
        ];
    }

    public function convertHexToRgba(string $hexColor, float $alpha = 1): string
    {
        // Supprimer le caractère '#' s'il est présent
        $hexColor = ltrim($hexColor, '#');

        // Convertir la couleur hexadécimale en RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        // Retourner le format rgba
        return "rgba($r, $g, $b, $alpha)";
    }
}
