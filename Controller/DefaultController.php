<?php

namespace EMS\LocalUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/demo-route")
     */
    public function indexAction()
    {
        return $this->render('EMSLocalUserBundle:Default:index.html.twig');
    }
}
