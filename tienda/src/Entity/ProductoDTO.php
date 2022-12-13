<?php
    namespace App\Entity;
    
    class ProductoDTO
    {
        public $titulo;
        public $fotoportada;
        public $fotos;
        public $fichaTecnica;
    
        public function __construct($titulo="", $fotoportada="",$fotos="",$fichaTecnica="")
        {
            $this->titulo = $titulo;
            $this->fotoportada = $fotoportada;
            $this->fotos = $fotos;
            $this->fichaTecnica = $fichaTecnica;
        }
    }
?>