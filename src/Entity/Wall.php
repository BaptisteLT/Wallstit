<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Traits\CreateUpdateTrait;
use App\Repository\WallRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: WallRepository::class)]
class Wall
{
    use CreateUpdateTrait;

    #[Groups(['get-walls'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['get-walls'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $backgroundColor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $borderColor = null;

    #[ORM\ManyToOne(inversedBy: 'walls')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[Groups(['get-walls'])]
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $description = null;

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

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): static
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->borderColor;
    }

    public function setBorderColor(?string $borderColor): static
    {
        $this->borderColor = $borderColor;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
