<?php

namespace App\Controller;

use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    #[Route('/base', name: 'app_base')]
    public function index(Request $request): Response
    {
        
        return $this->render('base.html.twig', [
            'controller_name' => 'BaseController',
            'formSearch'=>$form->createView(),
        ]);
    }
}
