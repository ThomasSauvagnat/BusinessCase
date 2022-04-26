<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get'],
    normalizationContext: ['groups' => 'myProducts']
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['myBrands', 'myCategories', 'myCommands', 'myProducts', 'myReviews'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['myBrands', 'myCategories','myCommands', 'myProducts', 'myReviews'])]
    #[Assert\Type(
        type: 'string',
        message: 'Le nom du produit doit être composé seulement de lettre'),
        Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Le nom du produit doit avoir au moins 3 caractères',
        maxMessage: 'Le nom du produit doit avoir au maximum 30 caractères',
    ), Assert\NotBlank(
        normalizer: 'trim'
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $label;

    #[ORM\Column(type: 'text')]
    #[Groups(['myProducts'])]
    #[Assert\NotBlank(
        normalizer: 'trim'
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    ), Assert\Length(
        min: 5,
        max: 500,
        minMessage: 'La description du produit doit avoir au moins 5 caractères',
        maxMessage: 'La description du produit doit avoir au maximum 500 caractères',
    )]
    private $description;

    #[ORM\Column(type: 'integer')]
    #[Groups(['myProducts'])]
    #[Assert\Positive(
        message: 'Le prix doit être positif'
    ), Assert\Type(
        type: 'integer',
        message: 'Le prix doit être un nombre.',
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $price;

    #[ORM\Column(type: 'integer')]
    #[Groups(['myProducts'])]
    #[Assert\Type(
        type: 'integer',
        message: 'Le stock doit être un nombre.',
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $stock;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['myProducts'])]
    #[Assert\Type(
        type: 'bool',
        message: 'Le champ doit être un booléen.',
    )]
    private $isActif;

    #[ORM\ManyToMany(targetEntity: Command::class, mappedBy: 'products')]
    #[Groups(['myProducts'])]
    private $commands;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'products')]
    #[Groups(['myProducts'])]
    #[ORM\JoinColumn(nullable: false)]
    private $brand;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Review::class)]
    #[Groups(['myProducts'])]
    private $reviews;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPicture::class)]
    #[Groups(['myProducts'])]
    private $productPictures;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'products')]
    #[Groups(['myProducts'])]
    private $categories;

    public function __construct()
    {
        $this->commands = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->productPictures = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getIsActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): self
    {
        $this->isActif = $isActif;

        return $this;
    }

    /**
     * @return Collection<int, Command>
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }

    public function addCommand(Command $command): self
    {
        if (!$this->commands->contains($command)) {
            $this->commands[] = $command;
            $command->addProduct($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            $command->removeProduct($this);
        }

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductPicture>
     */
    public function getProductPictures(): Collection
    {
        return $this->productPictures;
    }

    public function addProductPicture(ProductPicture $productPicture): self
    {
        if (!$this->productPictures->contains($productPicture)) {
            $this->productPictures[] = $productPicture;
            $productPicture->setProduct($this);
        }

        return $this;
    }

    public function removeProductPicture(ProductPicture $productPicture): self
    {
        if ($this->productPictures->removeElement($productPicture)) {
            // set the owning side to null (unless already changed)
            if ($productPicture->getProduct() === $this) {
                $productPicture->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduct($this);
        }

        return $this;
    }
}
