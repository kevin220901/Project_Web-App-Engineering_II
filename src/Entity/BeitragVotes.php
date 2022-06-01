<?php

namespace App\Entity;

use App\Repository\BeitragVotesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeitragVotesRepository::class)
 */
class BeitragVotes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Beitraege::class, inversedBy="beitragVotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $beitragID;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="beitragVotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBeitragID(): ?Beitraege
    {
        return $this->beitragID;
    }

    public function setBeitragID(?Beitraege $beitragID): self
    {
        $this->beitragID = $beitragID;

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
}
