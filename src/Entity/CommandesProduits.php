<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'commandes_produits')]
class CommandesProduits
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Commandes::class, inversedBy: 'commandesProduits')]
    #[ORM\JoinColumn(name: 'commande_id', referencedColumnName: 'id')]
    private ?Commandes $commande = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Produits::class, inversedBy: 'commandesProduits', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'produit_id', referencedColumnName: 'id')]
    private ?Produits $produit = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $montant = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $quantite = null;


    #[ORM\ManyToOne(targetEntity: Promos::class, cascade: ['persist'])]
    private ?Promos $promo = null;

    public function getPromo(): ?Promos
    {
        return $this->promo;
    }

    public function setPromo(?Promos $promo): self
    {
        $this->promo = $promo;
        return $this;
    }

    // Getters and setters

    public function getCommande(): ?Commandes
    {
        return $this->commande;
    }

    public function setCommande(?Commandes $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getProduit(): ?Produits
    {
        return $this->produit;
    }

    public function setProduit(?Produits $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getmontant(): ?string
    {
        return $this->montant;
    }

    public function setmontant(?string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
}
