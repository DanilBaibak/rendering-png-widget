<?php
/**
 * Created by PhpStorm.
 * User: Lamudi
 * Date: 12/05/15
 * Time: 14:37
 */

namespace Bundles\WidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UsersController extends Controller
{
    /**
     * TODO investigate http://symfony.com/blog/introducing-the-symfony-demo-application
     * Show list of the users
     *
     * @Route("/users")
     */
    public function usersListAction()
    {
        $users = $this->getDoctrine()->getRepository('WidgetBundle:Users')->findAll();

        return $this->render('WidgetBundle:users:users-list.html.twig', array('users' => $users));
    }
}