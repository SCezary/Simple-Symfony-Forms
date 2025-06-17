<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Template::class)]
    private ?Template $template = null;

    #[ORM\OneToMany(targetEntity: PostValue::class, mappedBy: 'post', cascade:  ['persist', 'remove'])]
    private ?Collection $postValues = null;

    public function __construct()
    {
        $this->postValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostValues(): ?Collection
    {
        return $this->postValues;
    }

    public function setTemplate(Template $template): self
    {
        $this->template = $template;
        return $this;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function clearPostValues(): void
    {
        $this->postValues = new ArrayCollection();
    }

    public function addPostValue(PostValue $postValue): self
    {
        if (!$this->postValues->contains($postValue)) {
            $this->postValues->add($postValue);
            $postValue->setPost($this);
        }

        return $this;
    }

    public function removePostValue(PostValue $postValue): self
    {
        if ($this->postValues->contains($postValue)) {
            $this->postValues->removeElement($postValue);

            if ($postValue->getPost() === $this) {
                $postValue->setPost(null);
            }
        }

        return $this;
    }
}
