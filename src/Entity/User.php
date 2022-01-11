<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 100)]
    private $firstName;

    #[ORM\Column(type: 'string', length: 100)]
    private $lastName;

    #[ORM\OneToMany(mappedBy: 'userRelation', targetEntity: SantaList::class)]
    private $santaLists;

    #[ORM\OneToMany(mappedBy: 'userRelation', targetEntity: Santa::class, orphanRemoval: true)]
    private $santas;

    public function __construct()
    {
        $this->santaLists = new ArrayCollection();
        $this->santas = new ArrayCollection();
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

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|SantaList[]
     */
    public function getSantaLists(): Collection
    {
        return $this->santaLists;
    }

    public function addSantaList(SantaList $santaList): self
    {
        if (!$this->santaLists->contains($santaList)) {
            $this->santaLists[] = $santaList;
            $santaList->setUserRelation($this);
        }

        return $this;
    }

    public function removeSantaList(SantaList $santaList): self
    {
        if ($this->santaLists->removeElement($santaList)) {
            // set the owning side to null (unless already changed)
            if ($santaList->getUserRelation() === $this) {
                $santaList->setUserRelation(null);
            }
        }

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
            $santa->setUserRelation($this);
        }

        return $this;
    }

    public function removeSanta(Santa $santa): self
    {
        if ($this->santas->removeElement($santa)) {
            // set the owning side to null (unless already changed)
            if ($santa->getUserRelation() === $this) {
                $santa->setUserRelation(null);
            }
        }

        return $this;
    }
}
