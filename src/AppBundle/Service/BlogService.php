<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\Tools\Pagination\Paginator;

use AppBundle\Entity\BlogRecord;

class BlogService
{

    private $pageLength = 4;

   private $doctrine;

public function getPageItems($pageIndex) 
    {
        $pageIndex = intval($pageIndex) -1;
	$em = $this->doctrine->getManager();
        $dql = "SELECT br, u FROM AppBundle:BlogRecord br JOIN br.user u ORDER BY br.createdAt DESC";
        $query = $em->createQuery($dql)
                       ->setFirstResult($pageIndex * $this->pageLength)
                       ->setMaxResults($this->pageLength);

        $paginator = new Paginator($query, $fetchJoinCollection = true);
        return $paginator;
    }

    public function find($id) 
    {
       $blogRepository = $this->doctrine->getRepository('AppBundle:BlogRecord');
       $blogRecord = $blogRepository->find($id);
       return $blogRecord;
    }

    public function getPageCount() 
    {
	$em = $this->doctrine->getManager();
        $dql = "SELECT COUNT(br) FROM AppBundle:BlogRecord br";
        $count = $query = $em->createQuery($dql)->getSingleScalarResult();
        return $count / $this->pageLength;
    }


    public function add($title, $description, $user) 
    {
	$em = $this->doctrine->getManager();
        $blogRecord = new BlogRecord();
        $blogRecord->setTitle($title)
              ->setDescription($description)
	      ->setUser($user);
	$em->persist($blogRecord);
        $em->flush();
    }

    public function edit($id, $title, $description) 
    {
        $blogRepository = $this->doctrine->getRepository('AppBundle:BlogRecord');
        $blogRecord = $blogRepository->find($id);
	$em = $this->doctrine->getManager();         
        $blogRecord->setTitle($title)
             ->setDescription($description);
	$em->persist($blogRecord);
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
