<?php
namespace App\Form;

use App\Entity\CommandesProduits;
use App\Entity\Produits;
use App\Entity\Promos;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandesProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produit', EntityType::class, [
                'class' => Produits::class,
                'choice_label' => 'nom',
                'label' => 'Produit'
            ])
            ->add('promo', EntityType::class, [
                'class' => Promos::class,
                'choice_label' => function (Promos $promo) {
                    return $promo->getIdProduit()->getNom(); // ou getId() ou getDescription()
                },
                'required' => false,
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommandesProduits::class,
        ]);
    }
}
