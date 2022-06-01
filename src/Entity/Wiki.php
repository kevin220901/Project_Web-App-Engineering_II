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
     * @ORM\Column(type="string", length=255)
     */
    private $wikiname;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $wikibild;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $startseite_md;

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

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="wikis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWikiname(): ?string
    {
        return $this->wikiname;
    }

    public function setWikiname(string $wikiname): self
    {
        $this->wikiname = $wikiname;

        return $this;
    }

    public function getWikibild()
    {
        return $this->wikibild;
    }

    public function setWikibild($wikibild): self
    {
        $this->wikibild = $wikibild;

        return $this;
    }

    public function getStartseiteMd(): ?string
    {
        return $this->startseite_md;
    }

    public function setStartseiteMd(?string $startseite_md): self
    {
        $this->startseite_md = $startseite_md;

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
