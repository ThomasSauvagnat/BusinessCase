<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\VisitsCountController;
use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VisitRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post',
        'get_visits_from_dates' => [
        'method' => 'GET',
        'path' => '/visits/get_total_visits',
        'controller' => VisitsCountController::class
        ]
    ],
    itemOperations: ['get']
)]

class Visit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    #[Assert\Type(
        type: 'datetime'
    ), Assert\NotNull(
        message: 'Le champ ne doit pas être nul'
    )]
    private $visitedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitedAt(): ?\DateTimeInterface
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(\DateTimeInterface $visitedAt): self
    {
        $this->visitedAt = $visitedAt;

        return $this;
    }
}
