<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicRepository")
 */
class Topic
{
    public function getDetails() {
        $details = [
            'id' => $this->getId(),
            'user' => $this->getUser()->getId(),
            'category' => $this->getCategory()->getId(),
            'title' => $this->getTitle(),
            'creationDate' => $this->getCreationDate()->format("Y-m-d H:i:s"),
            'pinned' => $this->getPinned()
        ];

        return $details;
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pinned;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="topic", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReadTopic", mappedBy="topic", orphanRemoval=true)
     */
    private $readTopics;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->readTopics = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getPinned(): ?bool
    {
        return $this->pinned;
    }

    public function setPinned(bool $pinned): self
    {
        $this->pinned = $pinned;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setTopic($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getTopic() === $this) {
                $message->setTopic(null);
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
            $readTopic->setTopic($this);
        }

        return $this;
    }

    public function removeReadTopic(ReadTopic $readTopic): self
    {
        if ($this->readTopics->contains($readTopic)) {
            $this->readTopics->removeElement($readTopic);
            // set the owning side to null (unless already changed)
            if ($readTopic->getTopic() === $this) {
                $readTopic->setTopic(null);
            }
        }

        return $this;
    }
}
