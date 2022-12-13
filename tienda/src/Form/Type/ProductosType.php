<?php
    namespace App\Form\Type;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\MoneyType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\File;
    use App\Entity\Producto;

    class ProductosType extends AbstractType
    {
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(["data_class" => Producto::class]);
        }
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->setMethod("POST")
                ->add("titulo", TextType::class)
                ->add("descripcion", TextType::class)
                ->add("precio",MoneyType::class,[
                    'help' => 'Introduzca el precio',
                    'required' => true,
                    'empty_data' => '0',
                    'label' => "Precio"
                ])
                ->add("iva", IntegerType::class)
                ->add("fotos", FileType::class,[
                    'label' => "Foto producto",
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => ["image/png","image/jpeg"],
                            'mimeTypesMessage' => "Debe ser una imagen"
                        ])
                    ]
                ])
                ->add("fotoportada",FileType::class,[
                    'label' => "Foto portada",
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => ["image/png","image/jpeg"],
                            'mimeTypesMessage' => "Debe ser una imagen"
                        ])
                    ]
                ])
                ->add("fichatecnica",FileType::class,[
                    'label' => "Ficha Tecnica",
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => ["application/pdf","application/x-pdf"],
                            'mimeTypesMessage' => "Debe ser un pdf"
                        ])
                    ]
                ])
                ->add("guardar",SubmitType::class);
        }

    }
?>