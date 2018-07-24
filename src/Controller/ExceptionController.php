<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExceptionController extends Controller
{
    public function showException()
    {
        return $this->render('bundles/twig-bundle/Exceptions/error404.html.twig');
    }
}