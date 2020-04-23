<?php

namespace WS\Core\Entity;

use WS\Core\Library\Traits\Entity\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ws_translation_attribute", uniqueConstraints={@ORM\UniqueConstraint(columns={"attrib_node", "attrib_name"})})
 */
class TranslationAttribute
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="attrib_id", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="TranslationNode")
     * @ORM\JoinColumn(name="attrib_node", referencedColumnName="node_id", nullable=false, nullable=false)
     */
    private $node;

    /**
     * @ORM\Column(name="attrib_name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @Assert\DateTime()
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="attrib_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @Assert\DateTime()
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="attrib_modified_at", type="datetime", nullable=false)
     */
    private $modifiedAt;

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

    public function setNode(TranslationNode $node)
    {
        $this->node = $node;

        return $this;
    }

    public function getNode() : ?TranslationNode
    {
        return $this->node;
    }
}
