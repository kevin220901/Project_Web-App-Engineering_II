<?php

namespace App\Entity;

use App\Repository\BeitraegeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeitraegeRepository::class)
 */
class Beitraege
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Wiki::class, inversedBy="beitraege")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wikiID;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="beitraege")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $inhalt_md;

    /**
     * @ORM\Column(type="datetime")
     */
    private $erstellt;

    /**
     * @ORM\OneToMany(targetEntity=BeitragVotes::class, mappedBy="beitragID")
     */
    private $beitragVotes;

    public function __construct()
    {
        $this->beitragVotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWikiID(): ?Wiki
    {
        return $this->wikiID;
    }

    public function setWikiID(?Wiki $wikiID): self
    {
        $this->wikiID = $wikiID;

        return $this;
    }

    public function getUserID(): ?User
    {
        return $this->userID;
    }

    public function setUserID(?User $userID): self
    {
        $this->userID = $userID;

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

    public function getInhaltMd(): ?string
    {
        return $this->inhalt_md;
    }

    public function setInhaltMd(string $inhalt_md): self
    {
        $this->inhalt_md = $inhalt_md;

        return $this;
    }

    public function getErstellt(): ?\DateTimeInterface
    {
        return $this->erstellt;
    }

    public function setErstellt(\DateTimeInterface $erstellt): self
    {
        $this->erstellt = $erstellt;

        return $this;
    }

    /**
     * @return Collection<int, BeitragVotes>
     */
    public function getBeitragVotes(): Collection
    {
        return $this->beitragVotes;
    }

    public function addBeitragVote(BeitragVotes $beitragVote): self
    {
        if (!$this->beitragVotes->contains($beitragVote)) {
            $this->beitragVotes[] = $beitragVote;
            $beitragVote->setBeitragID($this);
        }

        return $this;
    }

    public function removeBeitragVote(BeitragVotes $beitragVote): self
    {
        if ($this->beitragVotes->removeElement($beitragVote)) {
            // set the owning side to null (unless already changed)
            if ($beitragVote->getBeitragID() === $this) {
                $beitragVote->setBeitragID(null);
            }
        }

        return $this;
    }
}
