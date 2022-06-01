<?php

namespace App\Entity;

use App\Repository\UserIgnoreWikiRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserIgnoreWikiRepository::class)
 */
class UserIgnoreWiki
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Wiki::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $wikiID;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userIgnoreWikis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userID;

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
}
