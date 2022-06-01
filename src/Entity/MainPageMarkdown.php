<?php

namespace App\Entity;

use App\Repository\MainPageMarkdownRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MainPageMarkdownRepository::class)
 */
class MainPageMarkdown
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $markdown_md;

    /**
     * @ORM\Column(type="datetime")
     */
    private $erstellt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarkdownMd(): ?string
    {
        return $this->markdown_md;
    }

    public function setMarkdownMd(string $markdown_md): self
    {
        $this->markdown_md = $markdown_md;

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
}
