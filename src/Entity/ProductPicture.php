<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductPictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductPictureRepository::class)]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get']
)]
class ProductPicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['myProducts'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank, Assert\NotNull]
    private $path;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type(
        type: 'string',
        message: 'Le nom de l\'image doit être composé seulement de lettre'),
        Assert\Length(
        min: 1,
        max: 30,
        minMessage: 'Le nom de l\'image doit avoir au moins 1 caractères',
        maxMessage: 'Le nom de l\'image doit avoir au maximum 30 caractères',
    ), Assert\NotBlank(
        normalizer: 'trim'
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $libele;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productPictures')]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getLibele(): ?string
    {
        return $this->libele;
    }

    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

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
