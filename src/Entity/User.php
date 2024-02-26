<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Entity\Tokens\RefreshToken;
use App\Entity\Traits\CreateUpdateTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields: ['OAuth2Provider', 'OAuth2ProviderId'], message: 'This combination is already used.')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface
{
    use CreateUpdateTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[Groups(['get-user'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['get-user'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locale = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?RefreshToken $refreshToken = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Wall::class, orphanRemoval: true)]
    private Collection $walls;

    #[Groups(['get-post-its'])]
    #[Assert\Choice(['small', 'medium', 'large'])]
    #[ORM\Column(length: 255)]
    private ?string $sideBarSize = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $OAuth2Provider = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $OAuth2ProviderId = null;

    public function __construct()
    {
        $this->walls = new ArrayCollection();
        $this->sideBarSize = 'medium';
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
        return sprintf('%s_%s', $this->OAuth2Provider, $this->OAuth2ProviderId);
    }

    /**
     * J'ai implémenté cette méthode, autrement le JWT ne voulait pas se générer quand l'entité était dans un Proxy
     *
     * @return string
     */
    public function getUsername(): string
    {
        return sprintf('%s@@@%s', $this->OAuth2Provider, $this->OAuth2ProviderId);
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

    public function getSideBarSize(): ?string
    {
        return $this->sideBarSize;
    }

    public function setSideBarSize(string $sideBarSize): static
    {
        $this->sideBarSize = $sideBarSize;

        return $this;
    }

    public function getOAuth2Provider(): ?string
    {
        return $this->OAuth2Provider;
    }

    public function setOAuth2Provider(?string $OAuth2Provider): static
    {
        $this->OAuth2Provider = $OAuth2Provider;

        return $this;
    }

    public function getOAuth2ProviderId(): ?string
    {
        return $this->OAuth2ProviderId;
    }

    public function setOAuth2ProviderId(?string $OAuth2ProviderId): static
    {
        $this->OAuth2ProviderId = $OAuth2ProviderId;

        return $this;
    }

    
}
