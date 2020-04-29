<?php

namespace WS\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use WS\Core\Library\Domain\DomainDependantInterface;
use WS\Core\Library\Domain\DomainDependantTrait;

/**
 * @ORM\Entity(repositoryClass="WS\Core\Repository\ActivityLogRepository")
 * @ORM\Table(name="ws_activity_log", options={"collate"="utf8_unicode_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class ActivityLog implements DomainDependantInterface
{
    use DomainDependantTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="activity_log_id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="WS\Core\Entity\Domain")
     * @ORM\JoinColumn(name="activity_log_domain", referencedColumnName="domain_id", nullable=true)
     */
    private $domain;

    /**
     * @Assert\Length(max = 128)
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=128, name="activity_log_model", nullable=false)
     */
    protected $model;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer", name="activity_log_model_id", nullable=false)
     */
    protected $modelId;

    /**
     * @Assert\Length(max = 128)
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=128, name="activity_log_action", nullable=false)
     */
    protected $action;

    /**
     * @ORM\Column(type="json_array", name="activity_log_changes", nullable=true)
     */
    protected $changes;

    /**
     * @Assert\DateTime()
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="activity_log_created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @Assert\Length(max=128)
     * @Gedmo\Blameable(on="create")
     *
     * @ORM\Column(name="activity_log_created_by", type="string", length=128, nullable=true)
     */
    private $createdBy;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set model
     *
     * @param string $model
     * @return ActivityLog
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set model id
     *
     * @param int $modelId
     * @return ActivityLog
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * Get model id
     *
     * @return int
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return ActivityLog
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set changes
     *
     * @param array $changes
     * @return ActivityLog
     */
    public function setChanges($changes)
    {
        $this->changes = $changes;

        return $this;
    }

    /**
     * Get changes
     *
     * @return array
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     * Get createdAt
     *
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTimeInterface $createdAt
     * @return ActivityLog
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     * @return ActivityLog
     */
    public function setCreatedBy(string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
