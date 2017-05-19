<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{


public function doctrine() 
    {
       return $this->getDoctrine();
    }

}
