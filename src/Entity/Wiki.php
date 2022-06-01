<?php

namespace App\Entity;

use App\Repository\WikiRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WikiRepository::class)
 */
class Wiki
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $WikiName;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $WikiBild;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Startseite_md;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $privat_wiki;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $everyone_can_see;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $loggedin_can_see;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $can_user_request_to_join;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $loggedin_create_posts;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $loggedin_edit_posts;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $collab_edit_posts;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $erstellt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $allow_votes;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $wiki_banned;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setID(int $ID): self
    {
        $this->ID = $ID;

        return $this;
    }

    public function getWikiName(): ?string
    {
        return $this->WikiName;
    }

    public function setWikiName(string $WikiName): self
    {
        $this->WikiName = $WikiName;

        return $this;
    }

    public function getWikiBild()
    {
        return $this->WikiBild;
    }

    public function setWikiBild($WikiBild): self
    {
        $this->WikiBild = $WikiBild;

        return $this;
    }

    public function getStartseiteMd(): ?string
    {
        return $this->Startseite_md;
    }

    public function setStartseiteMd(?string $Startseite_md): self
    {
        $this->Startseite_md = $Startseite_md;

        return $this;
    }

    public function isPrivatWiki(): ?bool
    {
        return $this->privat_wiki;
    }

    public function setPrivatWiki(?bool $privat_wiki): self
    {
        $this->privat_wiki = $privat_wiki;

        return $this;
    }

    public function isEveryoneCanSee(): ?bool
    {
        return $this->everyone_can_see;
    }

    public function setEveryoneCanSee(?bool $everyone_can_see): self
    {
        $this->everyone_can_see = $everyone_can_see;

        return $this;
    }

    public function isLoggedinCanSee(): ?bool
    {
        return $this->loggedin_can_see;
    }

    public function setLoggedinCanSee(?bool $loggedin_can_see): self
    {
        $this->loggedin_can_see = $loggedin_can_see;

        return $this;
    }

    public function isCanUserRequestToJoin(): ?bool
    {
        return $this->can_user_request_to_join;
    }

    public function setCanUserRequestToJoin(?bool $can_user_request_to_join): self
    {
        $this->can_user_request_to_join = $can_user_request_to_join;

        return $this;
    }

    public function isLoggedinCreatePosts(): ?bool
    {
        return $this->loggedin_create_posts;
    }

    public function setLoggedinCreatePosts(?bool $loggedin_create_posts): self
    {
        $this->loggedin_create_posts = $loggedin_create_posts;

        return $this;
    }

    public function isLoggedinEditPosts(): ?bool
    {
        return $this->loggedin_edit_posts;
    }

    public function setLoggedinEditPosts(?bool $loggedin_edit_posts): self
    {
        $this->loggedin_edit_posts = $loggedin_edit_posts;

        return $this;
    }

    public function isCollabEditPosts(): ?bool
    {
        return $this->collab_edit_posts;
    }

    public function setCollabEditPosts(?bool $collab_edit_posts): self
    {
        $this->collab_edit_posts = $collab_edit_posts;

        return $this;
    }

    public function getErstellt(): ?\DateTimeInterface
    {
        return $this->erstellt;
    }

    public function setErstellt(?\DateTimeInterface $erstellt): self
    {
        $this->erstellt = $erstellt;

        return $this;
    }

    public function isAllowVotes(): ?bool
    {
        return $this->allow_votes;
    }

    public function setAllowVotes(?bool $allow_votes): self
    {
        $this->allow_votes = $allow_votes;

        return $this;
    }

    public function isWikiBanned(): ?bool
    {
        return $this->wiki_banned;
    }

    public function setWikiBanned(?bool $wiki_banned): self
    {
        $this->wiki_banned = $wiki_banned;

        return $this;
    }
}
