<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get'],
    normalizationContext: ['groups' => 'myCities']
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['myCities'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['myAdresses', 'myCities'])]
    #[Assert\Type(
        type: 'string',
        message: 'Le nom de la ville doit être composé seulement de lettre'),
        Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Le nom de la ville doit avoir au moins 3 caractères',
        maxMessage: 'Le nom de la ville doit avoir au maximum 30 caractères',
    ), Assert\NotBlank, Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $name;

    #[ORM\Column(type: 'integer')]
    #[Groups(['myAdresses', 'myCities'])]
    #[Assert\LessThan(99999),Assert\Positive(
        message: 'Le nombre doit être positif'
    ), Assert\NotBlank (
        normalizer: 'trim'
    ), Assert\Type(
        type: 'integer',
        message: 'La valeur doit être un nombre.',
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $cp;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Adress::class)]
    #[Groups(['myCities'])]
    private $adresses;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(int $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * @return Collection<int, Adress>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adress $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses[] = $adress;
            $adress->setCity($this);
        }

        return $this;
    }

    public function removeAdress(Adress $adress): self
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getCity() === $this) {
                $adress->setCity(null);
            }
        }

        return $this;
    }
}
