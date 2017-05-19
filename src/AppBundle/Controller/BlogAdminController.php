<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\BlogEntryForm;
use AppBundle\Service\LoginService;
use AppBundle\Service\BlogService;

class BlogAdminController extends BaseController
{

    public function doctrine() 
    {
       return $this->getDoctrine();
    }

    private function _createContentForm($title, $description, $label) 
    {
       $formData = new BlogEntryForm();
       $formData->setTitle($title)
            ->setDescription($description);

       $form = $this->createFormBuilder($formData)
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
	    ->add('save', SubmitType::class, array('label' => $label))
            ->getForm();
       return $form;
    }

    private function _createContentFormFor($id) 
    {
       $blogRecord = BlogService::inst($this)->find($id);

       $form = $this->_createContentForm($blogRecord->getTitle(), $blogRecord->getDescription(), "Edit");
       return $form;
    }

    private function _checkPermission($blogRecord) {
       if ( ! LoginService::inst()->isUser($blogRecord->getUser()->getName()))
          throw $this->createAccessDeniedException();
    }

    /**
     * @Route("/blog-admin/add")
     */
    public function addAction(Request $request)
    {
       $form = $this->_createContentForm("", "", "Add");

       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) 
       {
           $formData = $form->getData();
	   	   
           $user = LoginService::inst($this)->getLoggedUser();

	   BlogService::inst($this)->add($formData->getTitle(), $formData->getDescription(), $user);

           return $this->redirect('/');
       }

       return $this->render('blog-admin/add.html.twig', array(
            'form' => $form->createView(),
       ));
    }

    /**
     * @Route("/blog-admin/edit/{id}")
     */
    public function editAction(Request $request, $id)
    {
       $blogRecord = BlogService::inst($this)->find($id);

       $this->_checkPermission($blogRecord);

       $form = $this->_createContentFormFor($id);

       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) 
       {
           $formData = $form->getData();
	   
           $user = LoginService::inst($this)->getLoggedUser();

           BlogService::inst($this)->edit($id, $formData->getTitle(), $formData->getDescription());	  

           return $this->redirect('/');
       }

       return $this->render('blog-admin/edit.html.twig', array(
            'form' => $form->createView(),
       ));
    }
}
