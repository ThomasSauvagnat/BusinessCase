<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get'],
    // Création de group pour les adresses
    normalizationContext: ['groups' => 'myAdresses']
)]
class Adress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(['myAdresses', 'myCities', 'myCommands','myUsers'])]
    #[Assert\Positive(
        message: 'Le nombre doit être positif'
    ), Assert\NotBlank, Assert\Type(
        type: 'integer',
        message: 'La valeur doit être un nombre.',
    )]
    private $streetNumber;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['myAdresses', 'myCities', 'myCommands', 'myUsers'])]
    #[Assert\Type('string'), Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Le nom de la rue doit avoir au moins 1 caractères',
        maxMessage: 'Le nom de la rue doit avoir au maximum 255 caractères',
    )]
    private $streetName;

    #[ORM\OneToMany(mappedBy: 'adress', targetEntity: Command::class)]
    #[Groups(['myAdresses'])]
    private $commands;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'adresses')]
    private $users;

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'adresses')]
    #[Groups(['myAdresses'])]
    #[ORM\JoinColumn(nullable: false)]
    private $city;

    public function __construct()
    {
        $this->commands = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(int $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;

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
            $command->setAdress($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getAdress() === $this) {
                $command->setAdress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addAdress($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeAdress($this);
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
