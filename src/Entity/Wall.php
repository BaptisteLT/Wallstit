<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Traits\CreateUpdateTrait;
use App\Repository\WallRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


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

    #[Assert\Length(
        max: 50,
        maxMessage: 'The wall name be longer than {{ limit }} characters',
    )]
    #[Groups(['get-walls', 'get-post-its'])]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[Groups(['get-post-its'])]
    #[ORM\ManyToOne(inversedBy: 'walls')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[Assert\Length(
        max: 100,
        maxMessage: 'The wall description be longer than {{ limit }} characters',
    )]
    #[Groups(['get-walls', 'get-post-its'])]
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $description = null;

    #[Groups(['get-post-its'])]
    #[ORM\OneToMany(mappedBy: 'wall', targetEntity: PostIt::class, orphanRemoval: true)]
    private Collection $postIts;

    #[Groups(['get-post-its'])]
    #[Assert\Choice(['bricks', 'cork-board', 'flowers-colorful', 'flowers-dark', 'grouted-natural-stone', 'multi-coloured-tiles', 'wood', 'grid'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $background = null;

    public function __construct()
    {
        $this->postIts = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, PostIt>
     */
    public function getPostIts(): Collection
    {
        return $this->postIts;
    }

    public function addPostIt(PostIt $postIt): static
    {
        if (!$this->postIts->contains($postIt)) {
            $this->postIts->add($postIt);
            $postIt->setWall($this);
        }

        return $this;
    }

    public function removePostIt(PostIt $postIt): static
    {

        if ($this->postIts->contains($postIt)) {
            $this->postIts->removeElement($postIt);
            $postIt->setWall(null);
        }

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(?string $background): static
    {
        $this->background = $background;

        return $this;
    }
}
