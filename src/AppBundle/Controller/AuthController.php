<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\LoginForm;
use AppBundle\Entity\RegisterForm;
use AppBundle\Service\LoginService;


class AuthController extends BaseController
{



    function _buildRegisterForm()
    {
       $registerData = new RegisterForm();
       $registerData->setName('')
            ->setPassword('')
            ->setPasswordRetry('');

       $form = $this->createFormBuilder($registerData)
            ->add('name', TextType::class)
            ->add('password', PasswordType::class)
	    ->add('password_retry', PasswordType::class)
            ->add('save', SubmitType::class, array('label' => 'Register'))
            ->getForm();
    
       return $form;
    }

    function _buildLoginForm()
    {
       $loginData = new LoginForm();
       $loginData->setName('')
            ->setPassword('');

       $form = $this->createFormBuilder($loginData)
            ->add('name', TextType::class)
            ->add('password', PasswordType::class)
            ->add('save', SubmitType::class, array('label' => 'Login'))
            ->getForm();
      return $form;
    }    

    /**
     * @Route("/register")
     */
    public function registerAction(Request $request)
    {
       $form = $this->_buildRegisterForm();

       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {

           $registerData = $form->getData();
	             
	   LoginService::inst($this)->createUser($registerData->getName(), $registerData->getPassword() );
	   
           return $this->redirect('\login');
       }

       return $this->render('auth/register.html.twig', array(
            'form' => $form->createView(),
       ));
    }

    /**
     * @Route("/login")
     */
    public function loginAction(Request $request)
    {

    	$form = $this->_buildLoginForm();

       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
           $loginData = $form->getData();
	   $loginResult = LoginService::inst($this)->tryLogin($loginData->getName(), $loginData->getPassword());
           if ($loginResult === true)
               return $this->redirect("/");
           else {
               echo $loginResult;
           }           
       }

       return $this->render('auth/login.html.twig', array(
            'form' => $form->createView(),
       ));
    }

    /**
     * @Route("/logout")
     */
    public function logoutAction()
    {
       LoginService::inst()->logout();
       return $this->redirect("/");
    }
}
