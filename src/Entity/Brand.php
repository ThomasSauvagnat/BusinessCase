<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get'],
    normalizationContext: ['groups' => 'myBrands']
)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['myBrands','myProducts'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['myBrands', 'myProducts'])]
    #[Assert\Type('string'), Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Le label de la marque doit avoir au moins 2 caractères',
        maxMessage: 'Le label de la marque doit avoir au maximum 20 caractères',
    ), Assert\NotBlank, Assert\NotNull]
    private $label;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['myBrands'])]
    #[Assert\NotBlank, Assert\NotNull]
    private $imagePath;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Product::class)]
    #[Groups(['myBrands'])]
    private $products;

    public function __construct()
    {
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

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

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
            $product->setBrand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getBrand() === $this) {
                $product->setBrand(null);
            }
        }

        return $this;
    }
}
