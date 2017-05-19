<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\User;

class LoginService
{

   private $doctrine;

     public function getLoggedUser(){
       return $this->getUser($this->getUserName());
    }

   public function getUser($name){
       $userRepository = $this->doctrine->getRepository('AppBundle:User');
       $user = $userRepository->findOneByName($name);
       return $user;
    }

    public function getUserName(){
        $session = new Session();
        if (!$session->has('user')) return false;
        return $session->get('user');
    }

     function generatePasswordHash($password)
    {  
       $password = ''.$password;
       $hash1 = md5($password);           
       $salt = 'sflprt49fhi2';            
       $saltedHash = md5($hash1 . $salt);
       return $saltedHash; 
    }

    public function setLoginUserName($userName) 
    {
       $session = new Session();
       $session->set('user', $userName);
    }

    public function logout() 
    {
       $session = new Session();
       $session->remove('user');
    }

    public function isUser($username) {
	return $this->getUserName() == $username;
    }
  
    public function tryLogin($name, $password) 
    {
       $repository = $this->doctrine->getRepository('AppBundle:User');
       $user = $repository->findOneByName($name);
       if ($user == NULL) {
           return "No user ".$name;
       }
       
       if ($user->getPasswordHash() == $this->generatePasswordHash($password)) 
       {
           $this->setLoginUserName($name);
           return true;
       }       
       return "Invalid password";
    }

    public function createUser($name, $password) {
$passwordHash = $this->generatePasswordHash($password);
       $em = $this->doctrine->getManager();
           $user = new User();
           $user->setName($name)
                ->setPasswordHash($passwordHash);
	   $em->persist($user);
           $em->flush();
    }

    private static $_instance = null;
    private function __construct() {}   
    protected function __clone() {}
    static public function inst($controller= null) {
       if(self::$_instance == null)
       {
          self::$_instance = new self();
       }
       if ($controller != null) {
          self::$_instance->doctrine = $controller->doctrine();
       }
       return self::$_instance;
    }
}
