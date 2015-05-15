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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Bundles\WidgetBundle\Form\Type\UserType;
use Bundles\WidgetBundle\Form\Type\ImageType;
use Bundles\WidgetBundle\Entity\Users;
use Bundles\WidgetBundle\Entity\CustomGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UsersController extends Controller
{
    /**
     * TODO investigate http://symfony.com/blog/introducing-the-symfony-demo-application
     * Show list of the users
     *
     * @Route("/users", name="users")
     */
    public function usersListAction()
    {
        $users = $this->getDoctrine()->getRepository('WidgetBundle:Users')->findAll();

        return $this->render('WidgetBundle:users:users-list.html.twig', array('users' => $users));
    }

    /**
     * @param $hash
     *
     * @Route("/user_edit/{hash}", requirements={"hash" = "\d+"}, name="user_edit/{hash}")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userEditAction($hash = null)
    {
        $user = $this->getDoctrine()->getRepository('WidgetBundle:Users')->findOneBy([
            'hash' => $hash
        ]);

        $form = $this->createForm(new UserType(), $user, array('action' => $this->generateUrl('update_user')));

        return $this->render(
            'WidgetBundle:users:user-edit.html.twig',
            array(
                'form' => $form->createView(),
                'button_label' => isset($user) ? 'Update' : 'Create'
            )
        );
    }

    /**
     * Update/create users
     * TODO - add error handler
     *
     * @Route("/update_user", name="update_user")
     * @Method("POST")
     */
    public function updateUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new Users;
        $form = $this->createForm(new UserType(), $user);
        $form->handleRequest($request);

        //check is data valid
        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $this->getDoctrine()->getRepository('WidgetBundle:Users')->findOneBy([
                'hash' => $form->getData()->getHash()
            ]);

            //Update current user or create new one
            if ($currentUser) {
                $user = $currentUser;
                $form = $this->createForm(new UserType(), $user);
                $form->handleRequest($request);
            } else {
                //generate unique hash for each user
                $customerGenerator = new CustomGenerator();
                $user->setHash($customerGenerator->generate($em, $user));
            }

            $em->persist($user);
            $em->flush();
        } else {
            //TODO - handle not valid form
        }

        return $this->redirectToRoute('users');
    }

    /**
     * TODO - finish current functionality
     *
     * @param $hash
     * @Route("/delete_user/{hash}", requirements={"hash" = "\d+"}, name="delete_user/{hash}")
     * @Method("POST")
     */
    public function deleteUserAction($hash)
    {
        $user = $this->getDoctrine()->getRepository('WidgetBundle:Users')->findOneBy(['hash' => $hash]);
    }

    /**
     * @Route("/create_image", name="/create_image", requirements={"_method" = "POST"})
//     * @Route("/create_image/{hash}", requirements={"hash" = "\d+"}, name="create_image/{hash}", requirements={"_method" = "GET"})
//     * @Method("POST")
//     * @Method("GET")
     */
    public function createImageRequestAction(Request $request, $hash)
    {
        $form = $this->createForm(new ImageType());

        if ($hash) {
            $user = $this->getDoctrine()->getRepository('WidgetBundle:Users')->findOneBy(['hash' => $hash]);

            if ($user) {
                $form->get('hash')->setData($user->gethash());
            } else {
                throw new NotFoundHttpException();
            }
        } else {
            var_dump($request); die;
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('WidgetBundle:users:create-image.html.twig', array('form' => $form->createView()));
    }
}