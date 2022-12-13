<?php

    namespace App\Controller;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\Session; 
    use Symfony\Component\Routing\Annotation\Route; 
    use Symfony\Component\HttpFoundation\File\File;
    use Symfony\Component\HttpFoundation\ResponseHeaderBag;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
    use App\Services\ProductService;
    /** 
	 * @Route("/webservice", name="noticias") 
	 */
    class webserviceController extends AbstractController
    {
        /** 
         * @Route("/listarProductos", name="noticias2") 
         */
        public function listarProductos(ProductService $servicio)
        {
            $this->json($servicio->todosProductos());
        }
        /**
        * @Route("/detalleProducto/{titulo}", name="detalleProducto")
        */
        public function detalleProducto(ProductService $servicio, $titulo)
        {
            $this->json($servicio->unProducto($titulo));
        } 
    }
