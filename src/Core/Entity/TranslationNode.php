<?php

namespace WS\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ws_translation_node", uniqueConstraints={@ORM\UniqueConstraint(columns={"node_name"})})
 */
class TranslationNode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="node_id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="node_name", type="string", length=32, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="node_source", type="string", length=12, nullable=true)
     */
    private $source;

    /**
     * @ORM\Column(name="node_type", type="string", length=12, nullable=false)
     */
    private $type;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setName($name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setSource($source) : self
    {
        $this->source = $source;

        return $this;
    }

    public function getSource() : ?string
    {
        return $this->source;
    }

    public function setType($type) : self
    {
        $this->type = $type;

        return $this;
    }

    public function getType() : self
    {
        return $this->type;
    }

    public function getSourcePath() : string
    {
        $source = '';
        if ($this->source) {
            $source = $this->source . '.';
        }

        return $source;
    }
}
