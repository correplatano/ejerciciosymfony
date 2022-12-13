<?php
	namespace App\Controller;
   	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Contracts\Translation\TranslatorInterface;
   	
	use App\Services\ProductService; 
 
   	class DefaultController extends AbstractController
	{
		public function index(ProductService $servicio, TranslatorInterface $translator):Response 
		{
			$ejemplo_traduccion = $translator->trans("Hola Mundo");
			//$ejemplo_traduccion = $translator->trans(educacion.saludo);
			$productos = $servicio->todosProductos();
			return $this->render("base.html.twig",["productos"=>$productos]);
        }
	}
?>