<?php
/**
 * Created by PhpStorm.
 * User: Danil Baibak danil.baibak@gmail.com
 * Date: 21/04/15
 * Time: 16:38
 */
namespace Bundles\WidgetBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Bundles\WidgetBundle\Utils\ColorUtil;

/**
 * Class ImageService
 *
 * @Service("image.manager")
 */
class ImageService
{
    const FONT_SIZE = 20;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var ColorUtil
     */
    private $colorUtil;

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
        $this->optionsResolver = $this->configureOptionsResolver();
        $this->colorUtil = new ColorUtil();
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->_em;
    }

    /**
     * @param EntityManager $em
     */
    private function setEntityManager($em)
    {
        $this->_em = $em;
    }

    /**
     * Make validation of the image options
     *
     * @return OptionsResolver
     */
    private function configureOptionsResolver()
    {
        $isValidBetween = function ($value, $min, $max) {
            return $value >= $min && $value <= $max;
        };

        return (new OptionsResolver())
            ->setDefaults([
                'width'     => 100,
                'height'    => 100,
                'bgColor'   => '000000',
                'textColor' => 'FFFFFF'
            ])
            ->setAllowedValues([
                'width' => function ($value) use ($isValidBetween) {
                    return $isValidBetween($value, 100, 500);
                },
                'height' => function ($value) use ($isValidBetween) {
                    return $isValidBetween($value, 100, 500);
                },
                'bgColor' => function ($value) {
                    return $this->colorUtil->isValidHexColor($value);
                },
                'textColor' => function ($value) {
                    return $this->colorUtil->isValidHexColor($value);
                }
            ]);
    }

    /**
     * Generate image
     *
     * @param $user
     * @param $width
     * @param $height
     * @param $bgColor
     * @param $textColor
     * @return bool|string
     */
    public function getWidgetImage($user, $width, $height, $bgColor, $textColor)
    {
        $response = false;
        $rate = $user->getRate();
        //validate incoming data
        $options = $this->optionsResolver->resolve(array(
            'width'     => $width,
            'height'    => $height,
            'bgColor'   => $bgColor,
            'textColor' => $textColor
        ));

        if ($rate) {
            $response = $this->createImage($rate . "%", $options);
        }

        return $response;
    }

    /**
     * Create image by the configurations
     *
     * @param $text
     * @param $options
     * @return string
     */
    public function createImage($text, $options)
    {
        $newImg = imagecreate($options['width'], $options['height']);
        $backColors = $this->colorUtil->hex2rgb($options['bgColor']);
        $textColors = $this->colorUtil->hex2rgb($options['textColor']);
        $textPosition = $this->getTextPosition($options['width'], $options['height'], $text);

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
