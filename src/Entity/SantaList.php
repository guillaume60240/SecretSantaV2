<?php

namespace App\Entity;

use App\Repository\SantaListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SantaListRepository::class)]
class SantaList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'date')]
    private $eventDate;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $generated;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'santaLists')]
    private $userRelation;

    #[ORM\OneToMany(mappedBy: 'santaListRelation', targetEntity: Santa::class, orphanRemoval: true)]
    private $santas;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $sendMailToSantas;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $sendNotificationForGeneratedList;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $sendNotificationForSantaVisit;

    public function __construct()
    {
        $this->santas = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): self
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getGenerated(): ?bool
    {
        return $this->generated;
    }

    public function setGenerated(?bool $generated): self
    {
        $this->generated = $generated;

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

    /**
     * @return Collection|Santa[]
     */
    public function getSantas(): Collection
    {
        return $this->santas;
    }

    public function addSanta(Santa $santa): self
    {
        if (!$this->santas->contains($santa)) {
            $this->santas[] = $santa;
            $santa->setSantaListRelation($this);
        }

        return $this;
    }

    public function removeSanta(Santa $santa): self
    {
        if ($this->santas->removeElement($santa)) {
            // set the owning side to null (unless already changed)
            if ($santa->getSantaListRelation() === $this) {
                $santa->setSantaListRelation(null);
            }
        }

        return $this;
    }

    public function getSendMailToSantas(): ?bool
    {
        return $this->sendMailToSantas;
    }

    public function setSendMailToSantas(?bool $sendMailToSantas): self
    {
        $this->sendMailToSantas = $sendMailToSantas;

        return $this;
    }

    public function getSendNotificationForGeneratedList(): ?bool
    {
        return $this->sendNotificationForGeneratedList;
    }

    public function setSendNotificationForGeneratedList(?bool $sendNotificationForGeneratedList): self
    {
        $this->sendNotificationForGeneratedList = $sendNotificationForGeneratedList;

        return $this;
    }

    public function getSendNotificationForSantaVisit(): ?bool
    {
        return $this->sendNotificationForSantaVisit;
    }

    public function setSendNotificationForSantaVisit(?bool $sendNotificationForSantaVisit): self
    {
        $this->sendNotificationForSantaVisit = $sendNotificationForSantaVisit;

        return $this;
    }
}
