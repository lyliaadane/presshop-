<?php

namespace App\Form;

use App\Entity\Commandes;
use App\Entity\CommandesProduits;
use App\Entity\Produits;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\CommandesProduitsType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom_client', TextType::class, [
            'label' => 'Nom du client',
            'required' => true,
            'attr' => ['class' => 'form-control'],
        ])
        ->add('prenom_client', TextType::class, [
            'label' => 'Prénom du client',
            'required' => true,
            'attr' => ['class' => 'form-control'],
        ])
        ->add('mail_client', EmailType::class, [
            'label' => 'Email du client',
            'required' => true,
            'attr' => ['class' => 'form-control'],
        ])
        ->add('telephone_client', TextType::class, [
            'label' => 'Téléphone du client',
            'required' => true,
            'attr' => ['class' => 'form-control'],
        ])
        ->add('commandeProduits', CollectionType::class, [
            'entry_type' => CommandesProduitsType::class,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'delete_empty' => true,
            'label' => false,
            'attr' => ['style' => 'display:none;'] 
        ])
        ->add('date_recuperation', DateTimeType::class, [
            'label' => 'Date/Heure de récupération de la commande',
            'widget' => 'single_text',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'min' => (new \DateTime('now', new \DateTimeZone('Europe/Paris')))
                ->modify('+15 minutes')
                ->format('Y-m-d\TH:i'),
            ],
        ])
        ->add('commentaires', TextType::class, [
            'label' => 'Commentaires',
            'required' => false,  // S'il n'est pas obligatoire
            'attr' => ['class' => 'form-control'],
        ]);
       /* ->add('valider', SubmitType::class, [
            'label' => 'Procéder au Paiement',
            'attr' => ['class' => 'btn btn-success']
        ]);*/
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commandes::class,
        ]);
    }
}
