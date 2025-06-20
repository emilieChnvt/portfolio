<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $linkToGithub = null;

    #[ORM\OneToOne(inversedBy: 'projet', cascade: ['persist', 'remove'])]
    private ?Image $image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkToSite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLinkToGithub(): ?string
    {
        return $this->linkToGithub;
    }

    public function setLinkToGithub(string $linkToGithub): static
    {
        $this->linkToGithub = $linkToGithub;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLinkToSite(): ?string
    {
        return $this->linkToSite;
    }

    public function setLinkToSite(?string $linkToSite): static
    {
        $this->linkToSite = $linkToSite;

        return $this;
    }
}
