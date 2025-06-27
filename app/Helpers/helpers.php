<?php

if (!function_exists('getContrastColor')) {
    function getContrastColor($hexColor)
    {
        $hexColor = str_replace('#', '', $hexColor);

        if (strlen($hexColor) == 3) {
            $r = hexdec(str_repeat(substr($hexColor, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hexColor, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hexColor, 2, 1), 2));
        } else {
            $r = hexdec(substr($hexColor, 0, 2));
            $g = hexdec(substr($hexColor, 2, 2));
            $b = hexdec(substr($hexColor, 4, 2));
        }

        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        return $luminance > 0.5 ? '#000000' : '#FFFFFF'; // Black on light background, white on dark
    }
}
