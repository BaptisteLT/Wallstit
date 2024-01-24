<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locale = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?RefreshToken $refreshToken = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Wall::class, orphanRemoval: true)]
    private Collection $walls;

    public function __construct()
    {
        $this->walls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * J'ai implémenté cette méthode, autrement le JWT ne voulait pas se générer quand l'entité était dans un Proxy
     *
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function getRefreshToken(): ?RefreshToken
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?RefreshToken $refreshToken): static
    {
        // unset the owning side of the relation if necessary
        if ($refreshToken === null && $this->refreshToken !== null) {
            $this->refreshToken->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($refreshToken !== null && $refreshToken->getUser() !== $this) {
            $refreshToken->setUser($this);
        }

        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * @return Collection<int, Wall>
     */
    public function getWalls(): Collection
    {
        return $this->walls;
    }

    public function addWall(Wall $wall): static
    {
        if (!$this->walls->contains($wall)) {
            $this->walls->add($wall);
            $wall->setUser($this);
        }

        return $this;
    }

    public function removeWall(Wall $wall): static
    {
        if ($this->walls->removeElement($wall)) {
            // set the owning side to null (unless already changed)
            if ($wall->getUser() === $this) {
                $wall->setUser(null);
            }
        }

        return $this;
    }

    
}
