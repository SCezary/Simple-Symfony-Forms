<?php

namespace App\Entity;

use App\Repository\TemplateRepository;

use App\Service\EntityService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemplateRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Template
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $systemName = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Assert\NoSuspiciousCharacters]
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::SMALLINT, nullable: false)]
    private int $active = 1;

    #[Orm\OneToMany(targetEntity: TemplateField::class, mappedBy: 'template', cascade: ['persist', 'remove'])]
    private ?Collection $templateFields;

    public function __construct()
    {
        $this->templateFields = new ArrayCollection();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateSystemName(): void
    {
        $entityService = new EntityService();
        $this->systemName = $entityService->toSystemName($this->name);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSystemName(): ?string
    {
        return $this->systemName;
    }

    public function setSystemName(?string $systemName): void
    {
        $this->systemName = $systemName;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    public function getTemplateFields(): Collection
    {
        return $this->templateFields;
    }

    /**
     * @param Collection $templateFields
     */
    public function setTemplateFields(Collection $templateFields): void
    {
        $this->templateFields = $templateFields;
    }

    public function addTemplateField(TemplateField $templateField): self
    {
        if (!$this->templateFields->contains($templateField)) {
            $this->templateFields[] = $templateField;
            $templateField->setTemplate($this);
        }

        return $this;
    }

    public function removeTemplateField(TemplateField $templateField): self
    {
        if ($this->templateFields->contains($templateField)) {
            $this->templateFields->removeElement($templateField);
        }

        return $this;
    }

}
