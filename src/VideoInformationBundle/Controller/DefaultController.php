<?php

namespace VideoInformationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {        
        return $this->render('@VideoInformation/Default/index.html.twig');
    }
}
