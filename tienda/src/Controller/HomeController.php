<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session; 
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use App\Services\ProductService;

   
    class HomeController extends AbstractController
    {
        /**
        * @Route("{locale}/home/", name="inicio")
        */
        public function home(ProductService $servicio)
        {
            $productos = $servicio->todosProductos();
            return $this->render("home.html.twig",["productos"=>$productos]);
        }
    }
?>