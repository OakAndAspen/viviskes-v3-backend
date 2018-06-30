<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $celtName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $admin;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="user", orphanRemoval=true)
     */
    private $participations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSubject", mappedBy="user", orphanRemoval=true)
     */
    private $readSubjects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReadTopic", mappedBy="user", orphanRemoval=true)
     */
    private $readTopics;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
        $this->readSubjects = new ArrayCollection();
        $this->readTopics = new ArrayCollection();
    }

    public function getId()
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCeltName(): ?string
    {
        return $this->celtName;
    }

    public function setCeltName(?string $celtName): self
    {
        $this->celtName = $celtName;

        return $this;
    }

    public function getAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Participation[]
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
            $participation->setUser($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->contains($participation)) {
            $this->participations->removeElement($participation);
            // set the owning side to null (unless already changed)
            if ($participation->getUser() === $this) {
                $participation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserSubject[]
     */
    public function getReadSubjects(): Collection
    {
        return $this->readSubjects;
    }

    public function addReadSubject(UserSubject $readSubject): self
    {
        if (!$this->readSubjects->contains($readSubject)) {
            $this->readSubjects[] = $readSubject;
            $readSubject->setUser($this);
        }

        return $this;
    }

    public function removeReadSubject(UserSubject $readSubject): self
    {
        if ($this->readSubjects->contains($readSubject)) {
            $this->readSubjects->removeElement($readSubject);
            // set the owning side to null (unless already changed)
            if ($readSubject->getUser() === $this) {
                $readSubject->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReadTopic[]
     */
    public function getReadTopics(): Collection
    {
        return $this->readTopics;
    }

    public function addReadTopic(ReadTopic $readTopic): self
    {
        if (!$this->readTopics->contains($readTopic)) {
            $this->readTopics[] = $readTopic;
            $readTopic->setUser($this);
        }

        return $this;
    }

    public function removeReadTopic(ReadTopic $readTopic): self
    {
        if ($this->readTopics->contains($readTopic)) {
            $this->readTopics->removeElement($readTopic);
            // set the owning side to null (unless already changed)
            if ($readTopic->getUser() === $this) {
                $readTopic->setUser(null);
            }
        }

        return $this;
    }
}
