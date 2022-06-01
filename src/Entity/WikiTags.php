<?php

namespace App\Entity;

use App\Repository\WikiTagsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WikiTagsRepository::class)
 */
class WikiTags
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Wiki::class, inversedBy="wikiTags")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wikiID;

    /**
     * @ORM\ManyToOne(targetEntity=Tags::class, inversedBy="wikiTags")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tagID;

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

    public function getTagID(): ?Tags
    {
        return $this->tagID;
    }

    public function setTagID(?Tags $tagID): self
    {
        $this->tagID = $tagID;

        return $this;
    }
}
