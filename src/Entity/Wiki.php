<?php

namespace App\Entity;

use App\Repository\WikiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=WikiRepository::class)
 * @UniqueEntity(fields={"wikiname"}, message="Es gibt bereits ein Wiki mit diesem Namen!")
 * @Vich\Uploadable
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $wikiname;


    // Für das hochladen der Bilder
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Assert\File(
     *     maxSize = "8Mi",
     *     maxSizeMessage = "Die Datei ist zu größ ({{ size }} {{ suffix }})! Die maximal Größe ist {{ limit }} {{ suffix }}.",
     *     mimeTypes = {"image/png", "image/svg+xml", "image/gif", "image/jpg", "image/jpeg", "image/webp"},
     *     mimeTypesMessage = "Das verwendete Dateiformat ist ungültig! Folgende Typen sind erlaubt: {{ types }}",
     *     uploadErrorMessage = "Es kam zu einem Fehler während des Uploads der Datei!"
     * )
     * @Vich\UploadableField(mapping="wikiPB", fileNameProperty="imageName")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;
    // --------------------------
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

    /**
     * @ORM\OneToMany(targetEntity=Beitraege::class, mappedBy="wikiID")
     */
    private $beitraege;

    /**
     * @ORM\OneToMany(targetEntity=Wikiadmin::class, mappedBy="wikiID")
     */
    private $wikiadmins;

    /**
     * @ORM\OneToMany(targetEntity=Collaborator::class, mappedBy="wikiID")
     */
    private $collaborators;

    /**
     * @ORM\OneToMany(targetEntity=Wikivotes::class, mappedBy="wikiID")
     */
    private $wikivotes;

    /**
     * @ORM\OneToMany(targetEntity=BanedUsersFromWiki::class, mappedBy="wikiID")
     */
    private $banedUsersFromWikis;

    /**
     * @ORM\OneToMany(targetEntity=UserFavoriteWiki::class, mappedBy="wikiID")
     */
    private $userFavoriteWikis;

    /**
     * @ORM\OneToMany(targetEntity=UserIgnoreWiki::class, mappedBy="wikiID")
     */
    private $userIgnoreWikis;

    /**
     * @ORM\OneToMany(targetEntity=WikiTags::class, mappedBy="wikiID")
     */
    private $wikiTags;

    public function __construct()
    {
        $this->beitraege = new ArrayCollection();
        $this->wikiadmins = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
        $this->wikivotes = new ArrayCollection();
        $this->banedUsersFromWikis = new ArrayCollection();
        $this->userFavoriteWikis = new ArrayCollection();
        $this->userIgnoreWikis = new ArrayCollection();
        $this->wikiTags = new ArrayCollection();
    }

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
    // --------------------------------------------------
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    // --------------------------------------------------------------------

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
            $beitraege->setWikiID($this);
        }

        return $this;
    }

    public function removeBeitraege(Beitraege $beitraege): self
    {
        if ($this->beitraege->removeElement($beitraege)) {
            // set the owning side to null (unless already changed)
            if ($beitraege->getWikiID() === $this) {
                $beitraege->setWikiID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Wikiadmin>
     */
    public function getWikiadmins(): Collection
    {
        return $this->wikiadmins;
    }

    public function addWikiadmin(Wikiadmin $wikiadmin): self
    {
        if (!$this->wikiadmins->contains($wikiadmin)) {
            $this->wikiadmins[] = $wikiadmin;
            $wikiadmin->setWikiID($this);
        }

        return $this;
    }

    public function removeWikiadmin(Wikiadmin $wikiadmin): self
    {
        if ($this->wikiadmins->removeElement($wikiadmin)) {
            // set the owning side to null (unless already changed)
            if ($wikiadmin->getWikiID() === $this) {
                $wikiadmin->setWikiID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Collaborator>
     */
    public function getCollaborators(): Collection
    {
        return $this->collaborators;
    }

    public function addCollaborator(Collaborator $collaborator): self
    {
        if (!$this->collaborators->contains($collaborator)) {
            $this->collaborators[] = $collaborator;
            $collaborator->setWikiID($this);
        }

        return $this;
    }

    public function removeCollaborator(Collaborator $collaborator): self
    {
        if ($this->collaborators->removeElement($collaborator)) {
            // set the owning side to null (unless already changed)
            if ($collaborator->getWikiID() === $this) {
                $collaborator->setWikiID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Wikivotes>
     */
    public function getWikivotes(): Collection
    {
        return $this->wikivotes;
    }

    public function addWikivote(Wikivotes $wikivote): self
    {
        if (!$this->wikivotes->contains($wikivote)) {
            $this->wikivotes[] = $wikivote;
            $wikivote->setWikiID($this);
        }

        return $this;
    }

    public function removeWikivote(Wikivotes $wikivote): self
    {
        if ($this->wikivotes->removeElement($wikivote)) {
            // set the owning side to null (unless already changed)
            if ($wikivote->getWikiID() === $this) {
                $wikivote->setWikiID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BanedUsersFromWiki>
     */
    public function getBanedUsersFromWikis(): Collection
    {
        return $this->banedUsersFromWikis;
    }

    public function addBanedUsersFromWiki(BanedUsersFromWiki $banedUsersFromWiki): self
    {
        if (!$this->banedUsersFromWikis->contains($banedUsersFromWiki)) {
            $this->banedUsersFromWikis[] = $banedUsersFromWiki;
            $banedUsersFromWiki->setWikiID($this);
        }

        return $this;
    }

    public function removeBanedUsersFromWiki(BanedUsersFromWiki $banedUsersFromWiki): self
    {
        if ($this->banedUsersFromWikis->removeElement($banedUsersFromWiki)) {
            // set the owning side to null (unless already changed)
            if ($banedUsersFromWiki->getWikiID() === $this) {
                $banedUsersFromWiki->setWikiID(null);
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
            $userFavoriteWiki->setWikiID($this);
        }

        return $this;
    }

    public function removeUserFavoriteWiki(UserFavoriteWiki $userFavoriteWiki): self
    {
        if ($this->userFavoriteWikis->removeElement($userFavoriteWiki)) {
            // set the owning side to null (unless already changed)
            if ($userFavoriteWiki->getWikiID() === $this) {
                $userFavoriteWiki->setWikiID(null);
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
            $userIgnoreWiki->setWikiID($this);
        }

        return $this;
    }

    public function removeUserIgnoreWiki(UserIgnoreWiki $userIgnoreWiki): self
    {
        if ($this->userIgnoreWikis->removeElement($userIgnoreWiki)) {
            // set the owning side to null (unless already changed)
            if ($userIgnoreWiki->getWikiID() === $this) {
                $userIgnoreWiki->setWikiID(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, WikiTags>
     */
    public function getWikiTags(): Collection
    {
        return $this->wikiTags;
    }

    public function addWikiTag(WikiTags $wikiTag): self
    {
        if (!$this->wikiTags->contains($wikiTag)) {
            $this->wikiTags[] = $wikiTag;
            $wikiTag->setWikiID($this);
        }

        return $this;
    }

    public function removeWikiTag(WikiTags $wikiTag): self
    {
        if ($this->wikiTags->removeElement($wikiTag)) {
            // set the owning side to null (unless already changed)
            if ($wikiTag->getWikiID() === $this) {
                $wikiTag->setWikiID(null);
            }
        }

        return $this;
    }
}
