<?php
/**
 * Created by PhpStorm.
 * User: Danil Baibak danil.baibak@gmail.com
 * Date: 21/04/15
 * Time: 16:38
 */
namespace Bundles\WidgetBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * Class ImageService
 *
 * @Service("image.manager")
 */
class ImageService
{
    const FONT_SIZE = 20;
    /**
     * @var EntityManager
     */
    private $_em;

    /**
     * @param EntityManager $em Injected entity manager
     *
     * @InjectParams({
     *     "em" = @Inject("doctrine.orm.entity_manager"),
     * })
     */
    public function __construct(EntityManager $em) {
        $this->setEntityManager($em);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->_em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEntityManager($em)
    {
        $this->_em = $em;
    }

    /**
     * Generate image
     *
     * @param $userId
     * @param $width
     * @param $height
     * @param $bgColor
     * @param $textColor
     * @return bool|string
     */
    public function getWidgetImage($userId, $width, $height, $bgColor, $textColor)
    {
        $response = false;
        $rate = $this->getUserRate($userId);
        if ($rate) {
            $response = $this->createImage($rate . "%", $width, $height, $bgColor, $textColor);
        }

        return $response;
    }

    /**
     * Create image by the configurations
     *
     * @param $text
     * @param $width
     * @param $height
     * @param $bgColor
     * @param $textColor
     * @return string
     */
    public function createImage($text, $width, $height, $bgColor, $textColor)
    {
        $newImg = imagecreate($width, $height);
        $backColors = $this->hex2rgb($bgColor);
        $textColors = $this->hex2rgb($textColor);
        $textPosition = $this->getTextPosition($width, $height, $text);

        $background = imagecolorallocate($newImg, $backColors[0], $backColors[1], $backColors[2]);
        $textColour = imagecolorallocate($newImg, $textColors[0], $textColors[1], $textColors[2]);

        imagettftext(
            $newImg,
            self::FONT_SIZE,
            0,
            $textPosition['marginLeft'],
            $textPosition['marginTop'],
            $textColour,
            __DIR__ . '/../Resources/public/css/arial.ttf',
            $text
        );

        ob_start();
        imagepng($newImg);
        imagecolordeallocate($newImg, $textColour);
        imagecolordeallocate($newImg, $background);
        imagedestroy($newImg);
        $str = ob_get_clean();

        return $str;
    }

    /**
     * Get user's rate by user's id
     *
     * @param int $userId id of the current user
     * @return bool false or int user's rate
     */
    public function getUserRate($userId)
    {
        $response = $this->getEntityManager()
            ->getRepository('WidgetBundle:Users')
            ->getUserRate($userId);

        return empty($response) ? false : $response[0]['rate'];
    }

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
     * Calculate text position
     *
     * @param $width
     * @param $height
     * @param $text
     * @return array margin left and margin top
     */
    public function getTextPosition($width, $height, $text)
    {
        $textSize = imagettfbbox(self::FONT_SIZE, 0, __DIR__ . '/../Resources/public/css/arial.ttf', $text);
        $textWidth = $textSize[4] - $textSize[6];
        $marginLeft = $width / 2 - $textWidth / 2;

        $textHeight = $textSize[3] - $textSize[5];
        $marginTop = $height - ($height / 2 - $textHeight / 2);

        return array('marginLeft' => $marginLeft, 'marginTop' => $marginTop);
    }
}
