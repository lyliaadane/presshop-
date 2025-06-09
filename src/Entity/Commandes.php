<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_client = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom_client = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaires = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail_client = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone_client = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_commande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_recuperation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montant_total = null;

    #[ORM\Column]
    private ?int $statut = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: AcceptationsCGV::class)]
    private Collection $commandeCGV;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: AcceptationsPolitiqueConfidentialite::class)]
    private Collection $commandePolitiqueConfidentialite;


    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandesProduits::class, cascade: ['persist', 'remove'])]
    private Collection $commandeProduits;
    
    public function __construct()
    {
        $this->commandeProduits = new ArrayCollection();
        $this->commandeCGV = new ArrayCollection();
        $this->commandePolitiqueConfidentialite = new ArrayCollection();
      
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNomClient(): ?string
    {
        return $this->nom_client;
    }

    public function setNomClient(?string $nom_client): static
    {
        $this->nom_client = $nom_client;

        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): static
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    public function getPrenomClient(): ?string
    {
        return $this->prenom_client;
    }

    public function setPrenomClient(?string $prenom_client): static
    {
        $this->prenom_client = $prenom_client;

        return $this;
    }

    public function getMailClient(): ?string
    {
        return $this->mail_client;
    }

    public function setMailClient(?string $mail_client): static
    {
        $this->mail_client = $mail_client;

        return $this;
    }

    public function getTelephoneClient(): ?string
    {
        return $this->telephone_client;
    }

    public function setTelephoneClient(?string $telephone_client): static
    {
        $this->telephone_client = $telephone_client;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(?\DateTimeInterface $date_commande): static
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getDateRecuperation(): ?\DateTimeInterface
    {
        return $this->date_recuperation;
    }

    public function setDateRecuperation(?\DateTimeInterface $date_recuperation): static
    {
        $this->date_recuperation = $date_recuperation;

        return $this;
    }

    public function getMontantTotal(): ?string
    {
        return $this->montant_total;
    }

    public function setMontantTotal(?string $montant_total): static
    {
        $this->montant_total = $montant_total;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CommandesProduits $commandeProduit): self
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits[] = $commandeProduit;
            $commandeProduit->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandesProduits $commandeProduit): self
    {
        if ($this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits->removeElement($commandeProduit);
        }

        return $this;
    }

    public function getCommandeCGV(): Collection
    {
        return $this->commandeCGV;
    }
    public function getCommandePolitiqueConfidentialite(): Collection
    {
        return $this->commandePolitiqueConfidentialite;
    }


}
