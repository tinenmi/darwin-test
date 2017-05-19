<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Controller\BaseController;
use AppBundle\Service\LoginService;
use AppBundle\Service\BlogService;

class BlogController extends BaseController
{

public function doctrine() 
    {
       return $this->getDoctrine();
    }

    private function _preparePageItems($items, $username) 
    {
       $result = [];
       foreach($items as $item) 
       {
          $result[]= [
             "id" => $item->getId(),
             "userName" => $item->getUser()->getName(),
             "title" => $item->getTitle(),
             "description" => $item->getDescription(),
             "allowEdit" => $item->getUser()->getName() == $username
	  ];
       }
       return $result;
    }

    private function _renderItemsPage($pageIndex) 
    {
	$pageCount = BlogService::inst($this)->getPageCount();		

        if ($pageIndex > 1 && $pageIndex > $pageCount) 
        {
           throw $this->createNotFoundException('');
        }

	$userName = LoginService::inst()->getUserName();
        $isLogged = $userName != false;

	$items = BlogService::inst($this)->getPageItems($pageIndex);       
	$preparedItems = $this->_preparePageItems($items, $userName);        

        return $this->render('blog/items.html.twig', array(
            'pageIndex' => $pageIndex,
            'pageCount' => $pageCount,
            'items' => $preparedItems,
            'userName' => $userName,
            'isLogged' => $isLogged,
        ));
    }


    /**
     * @Route("/")
     */
    public function homeAction()
    {

        $em = $this->getDoctrine()->getManager();

        return $this->_renderItemsPage(1);
    }

    /**
     * @Route("/pages/{pageIndex}")
     */
    public function pageAction($pageIndex)
    {
       if ($pageIndex == 0)
	return $this->redirect('/');
       return $this->_renderItemsPage($pageIndex);
    }
}
