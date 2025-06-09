<?php

namespace App\Entity;

use App\Repository\AcceptationsCGVRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcceptationsCGVRepository::class)]
class AcceptationsCGV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Commandes::class, inversedBy: 'commandeCGV')]
    #[ORM\JoinColumn(nullable: false)] // Assurez-vous qu'une commande est obligatoire
    private ?Commandes $commande = null;

    #[ORM\ManyToOne(targetEntity: CGV::class)]
    #[ORM\JoinColumn(nullable: false)] // Assurez-vous qu'une CGV est obligatoire
    private ?CGV $cgv = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_acceptation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): ?Commandes
    {
        return $this->commande;
    }

    public function setCommande(?Commandes $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function getCgv(): ?CGV
    {
        return $this->cgv;
    }

    public function setCgv(?CGV $cgv): static
    {
        $this->cgv = $cgv;

        return $this;
    }

    public function getDateAcceptation(): ?\DateTimeInterface
    {
        return $this->date_acceptation;
    }

    public function setDateAcceptation(\DateTimeInterface $date_acceptation): static
    {
        $this->date_acceptation = $date_acceptation;

        return $this;
    }
}
