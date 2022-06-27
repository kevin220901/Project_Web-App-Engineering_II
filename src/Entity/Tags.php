<?php

namespace App\Entity;

use App\Repository\TagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
Use Symfony\Component\Form\FormTypeInterface;

/**
 * @ORM\Entity(repositoryClass=TagsRepository::class)
 */
class Tags
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
    private $tagName;

    /**
     * @ORM\OneToMany(targetEntity=WikiTags::class, mappedBy="tagID")
     */
    private $wikiTags;

    public function __construct()
    {
        $this->wikiTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTagName(): ?string
    {
        return $this->tagName;
    }

    public function setTagName(string $tagName): self
    {
        $this->tagName = $tagName;

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
            $wikiTag->setTagID($this);
        }

        return $this;
    }

    public function removeWikiTag(WikiTags $wikiTag): self
    {
        if ($this->wikiTags->removeElement($wikiTag)) {
            // set the owning side to null (unless already changed)
            if ($wikiTag->getTagID() === $this) {
                $wikiTag->setTagID(null);
            }
        }

        return $this;
    }
}
