<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Partie", mappedBy="Joueur1")
     */
    private $partiesJoueur1;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Partie", mappedBy="Joueur2")
     */
    private $partiesJoueur2;

    public function __construct()
    {
        $this->partiesJoueur1 = new ArrayCollection();
        $this->partiesJoueur2 = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
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

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
        // TODO: Implement getSalt() method.
    }

    /**
     * @return Collection|Partie[]
     */
    public function getPartiesJoueur1(): Collection
    {
        return $this->partiesJoueur1;
    }

    public function addPartiesJoueur1(Partie $partiesJoueur1): self
    {
        if (!$this->partiesJoueur1->contains($partiesJoueur1)) {
            $this->partiesJoueur1[] = $partiesJoueur1;
            $partiesJoueur1->setJoueur1($this);
        }

        return $this;
    }

    public function removePartiesJoueur1(Partie $partiesJoueur1): self
    {
        if ($this->partiesJoueur1->contains($partiesJoueur1)) {
            $this->partiesJoueur1->removeElement($partiesJoueur1);
            // set the owning side to null (unless already changed)
            if ($partiesJoueur1->getJoueur1() === $this) {
                $partiesJoueur1->setJoueur1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Partie[]
     */
    public function getPartiesJoueur2(): Collection
    {
        return $this->partiesJoueur2;
    }

    public function addPartiesJoueur2(Partie $partiesJoueur2): self
    {
        if (!$this->partiesJoueur2->contains($partiesJoueur2)) {
            $this->partiesJoueur2[] = $partiesJoueur2;
            $partiesJoueur2->setJoueur2($this);
        }

        return $this;
    }

    public function removePartiesJoueur2(Partie $partiesJoueur2): self
    {
        if ($this->partiesJoueur2->contains($partiesJoueur2)) {
            $this->partiesJoueur2->removeElement($partiesJoueur2);
            // set the owning side to null (unless already changed)
            if ($partiesJoueur2->getJoueur2() === $this) {
                $partiesJoueur2->setJoueur2(null);
            }
        }

        return $this;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isEquals(User $user): bool
    {
        if ($this->getId() == $user->getId()) {
            return true;
        } else {
            return false;
        }
    }

    public function __toString()
    {
        return $this->getUsername();
    }
}

