<?php
namespace fucodo\registry\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use setasign\Fpdi\PdfParser\Type\PdfIndirectObjectReference;

/**
 * @Flow\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"namespace", "name"})}, indexes={@ORM\Index(columns={"namespace", "name"})})
 */
class RegistryEntry
{
    /**
     * @var string
     */
    protected string $namespace;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @ORM\Column(type="object", nullable=true)
     * @var object
     */
    protected $value;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @var \DateTimeImmutable
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->createdAt= new \DateTimeImmutable();
        $this->updatedAt= new \DateTimeImmutable();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt= new \DateTimeImmutable();
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): RegistryEntry
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): RegistryEntry
    {
        $this->name = $name;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): RegistryEntry
    {
        $this->value = $value;
        $this->type = 'string';
        if (is_object($value)) {
            $this->type = 'object';
            return $this;
        }
        if (is_int($value)) {
            $this->type = 'int';
            return $this;
        }
        if (is_float($value)) {
            $this->type = 'float';
            return $this;
        }
        if (is_string($value)) {
            $this->type = 'string';
            return $this;
        }
        if (is_null($value)) {
            $this->type = 'null';
            return $this;
        }
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): RegistryEntry
    {
        $this->type = $type;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): RegistryEntry
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $lastUpdatedAt): RegistryEntry
    {
        $this->updatedAt = $lastUpdatedAt;
        return $this;
    }
}
