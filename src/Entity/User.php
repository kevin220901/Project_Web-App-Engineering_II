<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
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
    private $email_valid;

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

    public function isEmailValid(): ?bool
    {
        return $this->email_valid;
    }

    public function setEmailValid(?bool $email_valid): self
    {
        $this->email_valid = $email_valid;

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
}
