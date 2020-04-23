<?php

namespace WS\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ws_setting", uniqueConstraints={@ORM\UniqueConstraint(columns={"setting_domain", "setting_name"})})
 */
class Setting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="setting_id", type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="setting_name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="setting_value", type="text", nullable=true)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="WS\Core\Entity\Domain")
     * @ORM\JoinColumn(name="setting_domain", referencedColumnName="domain_id", nullable=false)
     */
    protected $domain;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    public function setDomain(Domain $domain): self
    {
        $this->domain = $domain;

        return $this;
    }
}
