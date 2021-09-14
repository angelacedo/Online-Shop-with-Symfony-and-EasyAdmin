<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoggedController extends AbstractController
{

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(): Response
    {
        // Redirect if the user is not logged
        if (!$this->getUser()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('logged/profile.html.twig');
    }
    /**
     * @Route("/post-profile", name="post-profile")
     */
    public function postprofile(Request $request, UserPasswordEncoderInterface $encoder): Response
    {

        $user = $this->getUser();
        $email = $request->request->get('email');
        $actualemail = $this->getUser()->getEmail();
        $newpassword = $request->request->get('newpassword');
        $confirmnewpassword = $request->request->get('confirmnewpassword');

        $em = $this->getDoctrine()->getManager();
        $db = $em->getRepository(Users::class);

        $emailexist = $db->findOneBy(['email' => $email]); // Ask if the email introduced exists


        //Changing or not the email
        if ($emailexist == null) { // If not, it will be changed
            $emailexist = $db->findOneBy(['email' => $actualemail]);
            $emailexist->setEmail($email);
            $em->flush();
            $this->addFlash('success_email_changed', "Your changes has been applied successfully");
        } else if ($emailexist->getEmail() == $user->getEmail()) {
            $this->addFlash('error_email_change', "Same email, omited change");
        } else if ($emailexist != null) {
            $this->addFlash('error_email_change', "The email introduces already exists or is not valid, please try with another one ");
        }
        //If the email is on the db, it will not be changed
        //Changing or not the password
        if ($newpassword == $confirmnewpassword) {

            $encodedpw = $encoder->encodePassword($user, $newpassword);
            $this->getUser()->setPassword($encodedpw);
            $em->flush();
            $this->addFlash('success_password_change', "Password changed successfully");
            return $this->redirectToRoute('profile');
        } else {
            $this->addFlash('error_password_change', "Passwords does not match, please try again");
            return $this->redirectToRoute('profile');
        }
    }
}
