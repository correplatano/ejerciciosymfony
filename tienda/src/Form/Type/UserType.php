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
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;

    use App\Entity\User;

    class UserType extends AbstractType
    {
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(["data_class" => User::class]);
        }
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->setMethod("GET")
                ->add("username", TextType::class)
                ->add('password', PasswordType::class)
                ->add('rol', ChoiceType::class, ['mapped' => false, 'choices' =>[
                    'Usuario' => "ROLE_USER",
                    'Admin' => "ROLE_ADMIN",
                ]])
                ->add("guardar",SubmitType::class);
        }
    }
?>