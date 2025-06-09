<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Utilisateurs;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UtilisateursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isProfileEdit = $options['is_profile_edit'] ?? false;

        $builder
            ->add('nom')
            ->add('prenom')
            ->add('nom_utilisateur');

        if (!$isProfileEdit) {
            // Champs réservés à l'admin
            $builder
                ->add('password', PasswordType::class)
                ->add('id_role', EntityType::class, [
                    'class' => Role::class,
                    'choice_label' => 'id',
                ]);
        }

        // 👉 Toujours afficher ce champ
        $builder->add('plainPassword', PasswordType::class, [
            'label' => 'Nouveau mot de passe',
            'mapped' => false,
            'required' => false,
            'attr' => ['autocomplete' => 'new-password'],
        ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
            'is_profile_edit' => false, // 👈 Ajout de l’option personnalisée
        ]);
    }
}
