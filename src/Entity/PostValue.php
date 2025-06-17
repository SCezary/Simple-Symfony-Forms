<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PostValuesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostValuesRepository::class)]
class PostValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\ManyToOne(targetEntity: Template::class)]
    private ?Template $template = null;

    #[Assert\NotBlank]
    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: "postValues")]
    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Post $post = null;

    #[Assert\NotBlank]
    #[ORM\ManyToOne(targetEntity: TemplateField::class)]
    private ?TemplateField $templateField = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): void
    {
        $this->template = $template;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): void
    {
        $this->post = $post;
    }

    public function getTemplateField(): ?TemplateField
    {
        return $this->templateField;
    }

    public function setTemplateField(?TemplateField $templateField): void
    {
        $this->templateField = $templateField;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
