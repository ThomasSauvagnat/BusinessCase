<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UserCountNewClientsController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: ['get',
    'post',
    'get_total_new_clients_from_date' => [
        'method' => 'GET',
        'path' => '/users/get_new_clients',
        'controller' => UserCountNewClientsController::class
    ]
],
    itemOperations: ['get'],
    normalizationContext: ['groups' => 'myUsers']
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['myCommands', 'myReviews', 'myUsers'])]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['myCommands', 'myUsers'])]
    #[Assert\Email(
        message: 'Le mail n\'est pas un mail valide.',
    ), Assert\NotBlank(
        normalizer: 'trim'
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul.'
    )]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\Type('string'), Assert\Length(
        min: 5,
        max: 100,
        minMessage: 'Le mot de passe doit avoir au moins 5 caractères',
        maxMessage: 'Le mot de passe doit avoir au maximum 100 caractères',
    ), Assert\NotNull]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['myCommands', 'myReviews', 'myUsers'])]
    #[Assert\Type(
        type: 'string',
        message: 'Le prénom doit être composé seulement de lettres'),
        Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le prénom doit avoir au moins 3 caractères',
        maxMessage: 'Le prénom doit avoir au maximum 255 caractères',
    ), Assert\NotBlank(
        normalizer: 'trim'
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['myCommands', 'myReviews', 'myUsers'])]
    #[Assert\Type(
        type: 'string',
        message: 'Le nom doit être composé seulement de lettres'),
        Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le nom doit avoir au moins 3 caractères',
        maxMessage: 'Le nom doit avoir au maximum 30 caractères',
    ), Assert\NotBlank(
        normalizer: 'trim'
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $lastName;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Command::class)]
    #[Groups('myUsers')]
    private $commands;

    #[ORM\ManyToMany(targetEntity: Adress::class, inversedBy: 'users')]
    #[Groups('myUsers')]
    private $adresses;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
    #[Groups('myUsers')]
    private $reviews;

    #[ORM\Column(type: 'datetime')]
    #[Groups('myUsers')]
    #[Assert\Type(
        type: 'datetime'
    )]
    private $createdAt;

    public function __construct()
    {
        $this->commands = new ArrayCollection();
        $this->adresses = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // Fonction pour la connexion
    public function getUsername(): string
    {
        return (string) $this->email;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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
            $command->setUser($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getUser() === $this) {
                $command->setUser(null);
            }
        }

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
        }

        return $this;
    }

    public function removeAdress(Adress $adress): self
    {
        $this->adresses->removeElement($adress);

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
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

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
}
