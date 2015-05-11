<?php
/**
 * Created by PhpStorm.
 * User: Danil Baibak danil.baibak@gmail.com
 * Date: 08/05/15
 * Time: 15:34
 */

namespace Bundles\WidgetBundle\Tests\Units;

use Bundles\WidgetBundle\Utils\ColorUtil;

class ColorUtilTest extends \PHPUnit_Framework_TestCase
{
    private $colorUtil;

    public function setUp()
    {
        $this->colorUtil = new ColorUtil();
    }


    /**
     * Test function hex2rgb
     *
     * @dataProvider hex2RgbDataProvider
     *
     * @param $hex
     * @param array $rgb
     */
    public function testHex2Rgb($hex, array $rgb)
    {
        $this->assertEquals($rgb, $this->colorUtil->hex2rgb($hex));
    }

    /**
     *
     * @dataProvider isValidHexColorCodeDataProvider
     *
     * @param $hexColor
     * @param $isValid]
     */
    public function testIsValidHexColor($hexColor, $isValid)
    {
        $this->assertEquals($isValid, $this->colorUtil->isValidHexColor($hexColor));
    }

    public function hex2RgbDataProvider()
    {
        return array(
            array('#000000', array(0,0,0)),
            array('000000', array(0,0,0)),
            array('FFFFFF', array(255,255,255)),
            array('ffffff', array(255,255,255)),
            array('fff', array(255,255,255))
        );
    }


    public function isValidHexColorCodeDataProvider()
    {
        return [
            ['#000000', true],
            ['000000', true],
            ['FFFFFF', true],
            ['ffffff', true],
            ['fff', true],
            ['zzz', false],
            ['#zzz', false],
            ['zzzzzz', false],
            ['#zzzzzz', false],
            ['afafzz', false],
            ['', false],
            [null, false]
        ];
    }
}