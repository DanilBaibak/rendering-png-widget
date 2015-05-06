<?php
/**
 * Utils for working with colors
 *
 * User: Danil Baibak danil.baibak@gmail.com
 * Date: 06/05/15
 * Time: 11:53
 */

namespace Bundles\WidgetBundle\Utils;

class ColorUtil
{
    /**
     * Returns an array with the rgb values
     *
     * @param $hex
     * @return array
     */
    public function hex2rgb($hex)
    {
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        $rgb = array($r, $g, $b);

        return $rgb;
    }

    /**
     * Check is current value hex color
     *
     * @param $value
     * @return bool
     */
    public function isValidHexColor($value)
    {
        return (bool) preg_match('/^#?([A-Fa-f0-9]{3,6})$/', $value);
    }
}
