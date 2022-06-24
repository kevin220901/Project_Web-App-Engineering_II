<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="Es gibt bereits ein Account mit diesem Username!")
 * @UniqueEntity(fields={"email"}, message="Diese Email wird bereits verwendet!")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $user_banned;

    /**
     * @ORM\Column(type="datetime")
     */
    private $erstellt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notify_on_invite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notify_on_new_post_in_favorite_wiki;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notify_on_accepted_request;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notify_on_platfrom_info;

    /**
     * @ORM\OneToMany(targetEntity=Wiki::class, mappedBy="userID")
     */
    private $wikis;

    /**
     * @ORM\OneToMany(targetEntity=Beitraege::class, mappedBy="userID")
     */
    private $beitraege;

    /**
     * @ORM\OneToMany(targetEntity=BeitragVotes::class, mappedBy="userID")
     */
    private $beitragVotes;

    /**
     * @ORM\OneToMany(targetEntity=UserFavoriteWiki::class, mappedBy="userID")
     */
    private $userFavoriteWikis;

    /**
     * @ORM\OneToMany(targetEntity=UserIgnoreWiki::class, mappedBy="userID")
     */
    private $userIgnoreWikis;

    public function __construct()
    {
        $this->wikis = new ArrayCollection();
        $this->beitraege = new ArrayCollection();
        $this->beitragVotes = new ArrayCollection();
        $this->userFavoriteWikis = new ArrayCollection();
        $this->userIgnoreWikis = new ArrayCollection();
    }
    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = true; // default false aber Verify über Email funktioniert nicht

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function isUserBanned(): ?bool
    {
        return $this->user_banned;
    }

    public function setUserBanned(bool $user_banned): self
    {
        $this->user_banned = $user_banned;

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

    public function isNotifyOnInvite(): ?bool
    {
        return $this->notify_on_invite;
    }

    public function setNotifyOnInvite(bool $notify_on_invite): self
    {
        $this->notify_on_invite = $notify_on_invite;

        return $this;
    }

    public function isNotifyOnNewPostInFavoriteWiki(): ?bool
    {
        return $this->notify_on_new_post_in_favorite_wiki;
    }

    public function setNotifyOnNewPostInFavoriteWiki(bool $notify_on_new_post_in_favorite_wiki): self
    {
        $this->notify_on_new_post_in_favorite_wiki = $notify_on_new_post_in_favorite_wiki;

        return $this;
    }

    public function isNotifyOnAcceptedRequest(): ?bool
    {
        return $this->notify_on_accepted_request;
    }

    public function setNotifyOnAcceptedRequest(bool $notify_on_accepted_request): self
    {
        $this->notify_on_accepted_request = $notify_on_accepted_request;

        return $this;
    }

    public function isNotifyOnPlatfromInfo(): ?bool
    {
        return $this->notify_on_platfrom_info;
    }

    public function setNotifyOnPlatfromInfo(bool $notify_on_platfrom_info): self
    {
        $this->notify_on_platfrom_info = $notify_on_platfrom_info;

        return $this;
    }


    /**
     * @return Collection<int, Wiki>
     */
    public function getWikis(): Collection
    {
        return $this->wikis;
    }

    public function addWiki(Wiki $wiki): self
    {
        if (!$this->wikis->contains($wiki)) {
            $this->wikis[] = $wiki;
            $wiki->setUserID($this);
        }

        return $this;
    }

    public function removeWiki(Wiki $wiki): self
    {
        if ($this->wikis->removeElement($wiki)) {
            // set the owning side to null (unless already changed)
            if ($wiki->getUserID() === $this) {
                $wiki->setUserID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Beitraege>
     */
    public function getBeitraege(): Collection
    {
        return $this->beitraege;
    }

    public function addBeitraege(Beitraege $beitraege): self
    {
        if (!$this->beitraege->contains($beitraege)) {
            $this->beitraege[] = $beitraege;
            $beitraege->setUserID($this);
        }

        return $this;
    }

    public function removeBeitraege(Beitraege $beitraege): self
    {
        if ($this->beitraege->removeElement($beitraege)) {
            // set the owning side to null (unless already changed)
            if ($beitraege->getUserID() === $this) {
                $beitraege->setUserID(null);
            }
        }

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
            $beitragVote->setUserID($this);
        }

        return $this;
    }

    public function removeBeitragVote(BeitragVotes $beitragVote): self
    {
        if ($this->beitragVotes->removeElement($beitragVote)) {
            // set the owning side to null (unless already changed)
            if ($beitragVote->getUserID() === $this) {
                $beitragVote->setUserID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserFavoriteWiki>
     */
    public function getUserFavoriteWikis(): Collection
    {
        return $this->userFavoriteWikis;
    }

    public function addUserFavoriteWiki(UserFavoriteWiki $userFavoriteWiki): self
    {
        if (!$this->userFavoriteWikis->contains($userFavoriteWiki)) {
            $this->userFavoriteWikis[] = $userFavoriteWiki;
            $userFavoriteWiki->setUserID($this);
        }

        return $this;
    }

    public function removeUserFavoriteWiki(UserFavoriteWiki $userFavoriteWiki): self
    {
        if ($this->userFavoriteWikis->removeElement($userFavoriteWiki)) {
            // set the owning side to null (unless already changed)
            if ($userFavoriteWiki->getUserID() === $this) {
                $userFavoriteWiki->setUserID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserIgnoreWiki>
     */
    public function getUserIgnoreWikis(): Collection
    {
        return $this->userIgnoreWikis;
    }

    public function addUserIgnoreWiki(UserIgnoreWiki $userIgnoreWiki): self
    {
        if (!$this->userIgnoreWikis->contains($userIgnoreWiki)) {
            $this->userIgnoreWikis[] = $userIgnoreWiki;
            $userIgnoreWiki->setUserID($this);
        }

        return $this;
    }

    public function removeUserIgnoreWiki(UserIgnoreWiki $userIgnoreWiki): self
    {
        if ($this->userIgnoreWikis->removeElement($userIgnoreWiki)) {
            // set the owning side to null (unless already changed)
            if ($userIgnoreWiki->getUserID() === $this) {
                $userIgnoreWiki->setUserID(null);
            }
        }
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;


        return $this;
    }
}
