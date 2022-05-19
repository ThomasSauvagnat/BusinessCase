<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\CommandBasketsAverageController;
use App\Controller\CommandConversionBasketsController;
use App\Controller\CommandConversionController;
use App\Controller\CommandRecurrenceController;
use App\Controller\CommandsTotalSellsController;
use App\Controller\CommandTotalBasketsController;
use App\Controller\CommandTotalController;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
#[ApiResource(
    collectionOperations: ['get',
    'post',
    'get_total_sells_from_dates' => [
        'method' => 'GET',
        'path' => '/commands/get_total_sells',
        'controller' => CommandsTotalSellsController::class
    ],
    'get_total_baskets' => [
        'method' => 'GET',
        'path' => '/commands/get_total_baskets',
        'controller' => CommandTotalBasketsController::class
    ],
    'get_total_commands' => [
        'method' => 'GET',
        'path' => '/commands/get_total_commands',
        'controller' => CommandTotalController::class
    ],
    'get_baskets_average_from dates' => [
        'method' => 'GET',
        'path' => '/commands/get_baskets_average',
        'controller' => CommandBasketsAverageController::class
    ],
    'get_baskets_conversion_from dates' => [
        'method' => 'GET',
        'path' => '/commands/get_baskets_conversion',
        'controller' => CommandConversionBasketsController::class
    ],
    'get_commands_conversion_from dates' => [
        'method' => 'GET',
        'path' => '/commands/get_commands_conversion',
        'controller' => CommandConversionController::class
    ],
    'get_commands_recurrence_from dates' => [
        'method' => 'GET',
        'path' => '/commands/get_commands_recurrence',
        'controller' => CommandRecurrenceController::class
    ]
],
    itemOperations: ['get'],
    normalizationContext: ['groups' => 'myCommands']
)]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
#[ApiFilter(SearchFilter::class, properties: ['user.firstName' => 'exact'])]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['myCommands', 'myProducts', 'myUsers'])]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(['myAdresses', 'myCommands'])]
    #[Assert\Positive(
        message: 'Le nombre doit être positif'
    ), Assert\NotBlank (
        normalizer: 'trim'
    ), Assert\Type(
        type: 'integer',
        message: 'La valeur doit être un nombre.',
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $totalPrice;

    #[ORM\Column(type: 'integer')]
    #[Groups(['myAdresses', 'myCommands', 'myProducts','myUsers'])]
    #[Assert\Positive(
        message: 'Le nombre doit être positif'
    ), Assert\Type(
        type: 'string',
        message: 'La valeur doit être un nombre.',
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $numCommand;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['myCommands'])]
    #[Assert\Type(
        type: 'datetime'
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $createdAt;

    #[ORM\Column(type: 'integer')]
    #[Groups(['myCommands'])]
    #[Assert\Positive(
        message: 'Le nombre doit être positif'
    ), Assert\NotBlank (
        normalizer: 'trim'
    ), Assert\Type(
        type: 'integer',
        message: 'La valeur doit être un nombre.',
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $status;

    #[ORM\ManyToOne(targetEntity: Adress::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['myCommands'])]
    private $adress;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['myCommands'])]
    private $user;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'commands')]
    #[Groups(['myCommands'])]
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getNumCommand(): ?int
    {
        return $this->numCommand;
    }

    public function setNumCommand(int $numCommand): self
    {
        $this->numCommand = $numCommand;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): self
    {
        $this->adress = $adress;

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
