<?php
/**
 * Created by PhpStorm.
 * User: Danil Baibak danil.baibak@gmail.com
 * Date: 23/04/15
 * Time: 11:58
 */
namespace Bundles\WidgetBundle\Tests\Service;

use Bundles\WidgetBundle\Service\ImageService;
use Bundles\WidgetBundle\Tests\Entity\UserFakeRepository;

class ImageServiceTest extends \PHPUnit_Framework_TestCase
{
    private $imageService;

    public function __construct()
    {
        $emMock = $this->getEmMock();
        $this->imageService = new ImageService($emMock);
    }

    /**
     * Mock for \Doctrine\ORM\EntityManager
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEmMock()
    {
        $emMock  = $this->getMock('\Doctrine\ORM\EntityManager',
            array('getRepository', 'getClassMetadata', 'persist', 'flush'), array(), '', false);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue(new UserFakeRepository()));
        $emMock->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue((object)array('name' => 'aClass')));
        $emMock->expects($this->any())
            ->method('persist')
            ->will($this->returnValue(null));
        $emMock->expects($this->any())
            ->method('flush')
            ->will($this->returnValue(null));
        return $emMock;
    }

    /**
     * Test function hex2rgb
     */
    public function testHex2rgb()
    {
        $rgbWhite = $this->imageService->hex2rgb('fff');
        $rgbBlack = $this->imageService->hex2rgb('000');

        $this->assertEquals(array(255, 255, 255), $rgbWhite);
        $this->assertEquals(array(0, 0, 0), $rgbBlack);
    }

    /**
     * Test function for getTextPosition
     */
    public function testGetTextPosition()
    {
        $textPosition = $this->imageService->getTextPosition(100, 100, 'Test text');

        $this->assertEquals(array('marginLeft' => 0.5, 'marginTop' => 60), $textPosition);
    }

    /**
     * Check case with wrong userId
     */
    public function testGetWidgetImage()
    {
        $widgetImage = $this->imageService->getWidgetImage(1, 100, 100, '000', 'fff');

        $this->assertFalse($widgetImage);
    }

}