<?php

namespace App\Entity;

use App\Repository\SantaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SantaRepository::class)]
class Santa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private $token;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $email;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'santas')]
    #[ORM\JoinColumn(nullable: false)]
    private $userRelation;

    #[ORM\ManyToOne(targetEntity: SantaList::class, inversedBy: 'santas')]
    #[ORM\JoinColumn(nullable: false)]
    private $santaListRelation;

    #[ORM\OneToOne(targetEntity: self::class, cascade: ['persist', 'remove'])]
    private $giveGift;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserRelation(): ?User
    {
        return $this->userRelation;
    }

    public function setUserRelation(?User $userRelation): self
    {
        $this->userRelation = $userRelation;

        return $this;
    }

    public function getSantaListRelation(): ?SantaList
    {
        return $this->santaListRelation;
    }

    public function setSantaListRelation(?SantaList $santaListRelation): self
    {
        $this->santaListRelation = $santaListRelation;

        return $this;
    }

    public function getGiveGift(): ?self
    {
        return $this->giveGift;
    }

    public function setGiveGift(?self $giveGift): self
    {
        $this->giveGift = $giveGift;

        return $this;
    }
}
