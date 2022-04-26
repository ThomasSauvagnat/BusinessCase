<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get'],
    normalizationContext: ['groups' => 'myReviews']
)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['myProducts', 'myReviews', 'myUsers'])]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(['myReviews'])]
    #[Assert\Positive(
        message: 'La note doit être positif'
    ), Assert\Type(
        type: 'integer',
        message: 'La note doit être un nombre.',
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    ), Assert\LessThan(20)]
    private $note;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['myReviews'])]
    #[Assert\Type(
        type: 'datetime'
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $createdAt;

    #[ORM\Column(type: 'text')]
    #[Groups(['myReviews'])]
    #[Assert\NotBlank(
        normalizer: 'trim'
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    ), Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'L\'avis du produit doit avoir au moins 1 caractères',
        maxMessage: 'L\'avis du produit doit avoir au maximum 255 caractères',
    )]
    private $content;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['myReviews'])]
    private $user;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['myReviews'])]
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
