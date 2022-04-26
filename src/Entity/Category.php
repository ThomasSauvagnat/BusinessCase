<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get'],
    normalizationContext: ['groups' => 'myCategories']
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['myCategories', 'myProducts'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['myCategories', 'myProducts'])]
    #[Assert\Type('string'), Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'La catégorie doit avoir une longueur d\'au moins 2 caractères',
        maxMessage: 'La catégorie doit avoir une longueur de maximum 20 caractères',
    ), Assert\NotBlank, Assert\NotNull]
    private $label;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories')]
    private $categoryParent;

    #[ORM\OneToMany(mappedBy: 'categoryParent', targetEntity: self::class)]
    #[Groups(['myCategories', 'myProducts'])]
    private $categories;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'categories')]
    #[Groups(['myCategories'])]
    private $products;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function getCategoryParent(): ?self
    {
        return $this->categoryParent;
    }

    public function setCategoryParent(?self $categoryParent): self
    {
        $this->categoryParent = $categoryParent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(self $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setCategoryParent($this);
        }

        return $this;
    }

    public function removeCategory(self $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCategoryParent() === $this) {
                $category->setCategoryParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }
}
