<?php
    namespace App\Services;
    use App\Entity\ProductoDTO;
    use App\Repository\ProductoRepository;

    class ProductService
    {
        public $productRepository;

        public function __construct(ProductoRepository $productRepository)
        {
          $this->productRepository = $productRepository;
        }
        public function todosProductos()
        {
            return $this->productRepository->findAll();
        }
        public function unProducto($id)
        {
            return $this->productRepository->find($id);
        }
    }
?>