<?php

namespace App\Entity;

use App\Entity\Enums\TemplateFieldTypeEnum;
use App\Repository\TemplateFieldsRepository;
use App\Service\EntityService;
use Symfony\Component\Validator\Constraints as Assert;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemplateFieldsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class TemplateField
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $systemName = null;

    #[Assert\NotBlank]
    #[Assert\NoSuspiciousCharacters]
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $label = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::SMALLINT, nullable: false)]
    private int $required = 0;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $options = null;

    #[Orm\Column(name: "`order`", type: Types::INTEGER, nullable: true)]
    private ?int $order = null; // TODO: Finish Functionality

    #[ORM\Column(type: Types::TEXT, length: 80, nullable: false, enumType: TemplateFieldTypeEnum::class)]
    private ?TemplateFieldTypeEnum $type  = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Template::class, inversedBy: 'templateFields')]
    #[ORM\JoinColumn(name: 'template_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Template $template;

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateSystemName(): void
    {
        $entityService = new EntityService();
        $this->systemName = $entityService->toSystemName($this->label);
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSystemName(): ?string
    {
        return $this->systemName;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(?bool $required): void
    {
        $this->required = $required;
    }

    public function getType(): ?TemplateFieldTypeEnum
    {
        return $this->type;
    }

    public function setType(?TemplateFieldTypeEnum $type): void
    {
        $this->type = $type;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): void
    {
        $this->template = $template;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): void
    {
        $this->options = $options;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(?int $order): void
    {
        $this->order = $order;
    }

    /**
     * @return array
     */
    public function getOptionsAsAssocArray(): array
    {
        $options = [];
        foreach ($this->options as $option) {
            if (!empty($option['value']) && !empty($option['label'])) {
                $options[$option['value']] = $option['label'];
            }
        }

        return $options;
    }
}
