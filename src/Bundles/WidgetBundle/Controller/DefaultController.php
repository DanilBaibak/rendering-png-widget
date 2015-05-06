<?php

namespace Bundles\WidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Bundles\WidgetBundle\Entity\Users;

class DefaultController extends Controller
{
    /**
     * Generate images by current settings
     *
     * @param int $hash id of the user
     * @param int $width width of the image
     * @param int $height height of the image
     * @param string $bgColor background color
     * @param string $textColor text color
     *
     * @Route(
     *      "/get-rate/{hash}/{width}/{height}/{bgColor}/{textColor}",
     *      requirements={
     *          "hash": "([0-9]{1,3})",
     *          "width": "([0-9]{1,3})",
     *          "height": "([0-9]{1,3})",
     *          "bgColor": "[0-9a-fA-F]+",
     *          "textColor": "[0-9a-fA-F]+",
     *      }
     * )
     *
     * @return Response
     */
    public function indexAction($hash, $width, $height, $bgColor, $textColor)
    {
        /**
         * Get current user by hash
         */
        $user = $this->getDoctrine()->getRepository('WidgetBundle:Users')->findOneBy([
            'hash'   => $hash,
            'status' => Users::STATUS_ACTIVE
        ]);

        //If user exists
        if ($user) {
            //get image
            $str = $this->get('image.manager')->getWidgetImage($user, $width, $height, $bgColor, $textColor);

            if ($str) {
                $headers = array(
                    'Content-Type'        => 'image/png',
                    'Content-Disposition' => 'inline; filename="image.png"'
                );
                $statusCode = Response::HTTP_OK;
            } else {
                $str = '<html><body><h2>Method unavailable</h2></body></html>';
                $headers = array('Content-Type' => 'text/html');
                $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
            }
        } else {
            $str = '<html><body><h2>Current user cannot be found or inactive</h2></body></html>';
            $headers = array('Content-Type' => 'text/html');
            $statusCode = Response::HTTP_BAD_REQUEST;
        }

        return new Response($str, $statusCode, $headers);
    }
}
