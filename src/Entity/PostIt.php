<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostItRepository;
use App\Entity\Traits\CreateUpdateTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PostItRepository::class)]
class PostIt
{
    use CreateUpdateTrait;

    public function __construct()
    {
        //default values
        $this->color = 'yellow';
        $this->size = 'medium';
        $this->uuid = Uuid::v4();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;
    #private ?int $id = null;

    #[Groups(['get-post-its'])]
    #[Assert\NotBlank]
    #[ORM\Column(type: 'uuid')]
    private Uuid $uuid;

    #[Groups(['get-post-its'])]
    #[Assert\Choice(['yellow', 'green', 'orange', 'blue', 'pink'])]
    #[ORM\Column(length: 6)]
    private string $color;

    #[Groups(['get-post-its'])]
    #[Assert\Length(
        max: 500,
        maxMessage: 'The content cannot be longer than {{ limit }} characters',
    )]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[Groups(['get-post-its'])]
    #[Assert\Range(
        min: -150,
        max: 3800,
        notInRangeMessage: 'You are off limits',
    )]
    #[ORM\Column(nullable: true)]
    private ?int $positionX = null;

    #[Groups(['get-post-its'])]
    #[Assert\Range(
        min: -150,
        max: 2100,
        notInRangeMessage: 'You are off limits',
    )]
    #[ORM\Column(nullable: true)]
    private ?int $positionY = null;

    #[Assert\NotBlank]
    #[ORM\ManyToOne(inversedBy: 'postIts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wall $wall;

    #[Groups(['get-post-its'])]
    #[Assert\Choice(['small', 'medium', 'large'])]
    #[ORM\Column(length: 6)]
    private string $size;

    #[Groups(['get-post-its'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deadline = null;

    #[Groups(['get-post-its'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'The title cannot be longer than {{ limit }} characters',
    )]
    private ?string $title = null;

    #[Groups(['get-post-its'])]
    #[ORM\Column(nullable: true)]
    private ?bool $deadlineDone = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPositionX(): ?int
    {
        return $this->positionX;
    }

    public function setPositionX(?int $positionX): static
    {
        $this->positionX = $positionX;

        return $this;
    }

    public function getPositionY(): ?int
    {
        return $this->positionY;
    }

    public function setPositionY(?int $positionY): static
    {
        $this->positionY = $positionY;

        return $this;
    }

    public function getWall(): Wall
    {
        return $this->wall;
    }

    public function setWall(?Wall $wall): static
    {
        $this->wall = $wall;

        return $this;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getDeadline(): ?\DateTimeImmutable
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTimeImmutable $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function isDeadlineDone(): ?bool
    {
        return $this->deadlineDone;
    }

    public function setDeadlineDone(?bool $deadlineDone): static
    {
        $this->deadlineDone = $deadlineDone;

        return $this;
    }

}
