<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteRepository::class)]
class Compte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Numc = null;

    #[ORM\Column]
    private ?float $Solde = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'comptes')]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumc(): ?int
    {
        return $this->Numc;
    }

    public function setNumc(int $Numc): static
    {
        $this->Numc = $Numc;

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->Solde;
    }

    public function setSolde(float $Solde): static
    {
        $this->Solde = $Solde;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->DateCreation;
    }

    public function setDateCreation(\DateTimeInterface $DateCreation): static
    {
        $this->DateCreation = $DateCreation;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
