<?php

    namespace App\Controller;

    use Symfony\Component\Routing\Annotation\Route; 
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response; 
    use App\Repository\ProductoRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
    use Symfony\Component\HttpFoundation\Request;
    use App\Services\ProductService;
    use App\Form\Type\ProductosType;
    use Symfony\Component\String\Slugger\AsciiSlugger;

    use App\Entity\Producto;
use Exception;

    /**
    * @Route("/productos", name="productos")
    */
    class ProductosCRUD extends AbstractController
    {
        private $productoService;

        public function __construct(ProductService $productoService)
        {
            $this->productoService = $productoService;
        }
        /**
        * @Route("/todos", name="todos")
        */
        public function todos()
        {
            $productos = $this->productoService->todosProductos();
            return $this->render("productos.html.twig",["productos"=>$productos]);
        }

        /**
        * @Route("/id/{id}", name="unproductoid")
        */
        public function unproducto($id)
        {
            $producto = $this->productoService->unProducto($id);
            return $this->render("producto.html.twig",["producto"=>$producto]);
        }

        private function procesarImagen($imagen)
        {
            $slugger = new AsciiSlugger();
            if($imagen)
            {
                $nombreOriginal = pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME);
                //para almacenarlo no es buena idea guardarlo con el nombre original, porque
                //podemos tener una sobreescritura acccidental del fichero
                $nombreGuardar = $slugger->slug($nombreOriginal)."-".uniqid().".".$imagen->guessExtension();
                //Ahora ya podemos poner el codigo de mover el fichero a la ruta que le indiquemos
                try
                {
                    //ahora hacemos el equivalente en php de mover el fichero a la ruta definitiva
                    //con esto le pasamos la ruta a donde lo vamos a mover y el nombre del fichero
                    //es mucho mas sencillo que en PHP
                    $directory =  $this->getParameter('kernel.project_dir');
                    $ruta = $directory."\\uploads\\".date("F")."\\";
                    $fichero = $imagen->move($ruta,$nombreGuardar);

                    //TODO, ahora podriamos introducir el valor en el campo correspondiente de usuario
                    //de la ruta del fichero, en caso de que no queramos guardar todos los datos
                    //del file en nuestra entidad
                    return $ruta.$nombreGuardar;
                }
                catch(Exception $e)
                {
                    echo("Error al subir el fichero");
                    return false;
                }
            }
            else
            {
                return "";
            }
            return false;
        }

        private function formulario($producto,$request,ProductoRepository $productRepository)
        {

            $formulario = $this->createForm(ProductosType::class, $producto);
            //variable para mostrar un mensaje distinto en caso de error o alta ok
            $alta = "-1";
            //Le metemos el handle request para procesarlo
            $formulario->handleRequest($request);
            //Comprobamos si tiene datos
            if($formulario->isSubmitted())
            {
               
                if($formulario->isValid())
                {
                    //debemos hacer un get especifico para la imagen
                    $fotos = $formulario->get("fotos")->getData();   
                    $fotos = $this->procesarImagen($fotos);

                    $fotoportada = $formulario->get("fotoportada")->getData();
                    $fotoportada = $this->procesarImagen($fotoportada);
                   
                    $fichatecnica = $formulario->get("fichatecnica")->getData();
                    $fichatecnica = $this->procesarImagen($fichatecnica);
                    
                    if($fotos==false || $fotoportada ==false || $fichatecnica==false )
                    {
                        $alta = false;
                    }
                    else
                    {
                        $producto->setFotos($fotos);
                        $producto->setFotoportada($fotoportada);
                        $producto->setFichatecnica($fichatecnica);

                        //ponemos el codigo de guardar
                        $productRepository->add($producto, true);
                        $alta = true;
                
                    }
                }
                else
                {
                    $alta = false;
                }
            }
            $devolucion["formulario"] = $formulario;
            $devolucion["alta"] = $alta;

            return $devolucion;
        }
        /**
        * @Route("/editar/{id}", name="editarproducto")
        */
        public function editarproducto(Request $request, ProductoRepository $productRepository, $id)
        {
            $producto = $productRepository->find($id);
            $devolucion = $this->formulario($producto, $request,$productRepository);
            $formulario = $devolucion["formulario"];
            $alta = $devolucion["formulario"];

            return $this->render(
                                    'productos/formularioProducto.html.twig',
                                    [
                                        'formulario' => $formulario->createView(),
                                        'alta' => $alta
                                    ]
                                );
            
        }
        /**
        * @Route("/alta", name="altaproducto")
        */
        public function altaproducto(Request $request, ProductoRepository $productRepository)
        {

            $producto = new Producto();
            $devolucion = $this->formulario($producto, $request,$productRepository);
            $formulario = $devolucion["formulario"];
            $alta = $devolucion["alta"];

            return $this->render(
                                    'productos/formularioProducto.html.twig',
                                    [
                                        'formulario' => $formulario->createView(),
                                        'alta' => $alta
                                    ]
                                );
        }
        /**
        * @Route("/borrar/{id}", name="borrarproductoid")
        */
        public function borrarproducto($id,ProductoRepository $productRepository)
        {
            //Podemos bloquear por codigo acceso, esto equivale a bloquear
            //el acceso a la ruta pero por codigo sin ir al yaml
            //$this->denyAccessUnlessGranted("ROLE_ADMIN");
            //Tambien podemos dar acceso condicional
            //$this->isGranted("ROLE_ADMIN") //Esto devuelve true si el usuario tiene ese rol
           /*
            if $this->isGranted("ROLE_ADMIN")
           {
            //Acciones permitidas si eres admin
           }
           else
           {
            //Que hacemos si no eres admin
           }
           */
            $producto = $productRepository->find($id);
            if($producto!=null)
            {
                $productRepository->remove($producto,true);
                $productos = $this->productoService->todosProductos();
                return $this->render("productos.html.twig",["productos"=>$productos]);
            }
            else
            {
                return $this->render("error_producto.html.twig",["productos"=>$producto]);
            }
        }

    }
?>