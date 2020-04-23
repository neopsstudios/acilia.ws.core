<?php

namespace WS\Core\Service\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use WS\Core\Entity\ActivityLog;
use WS\Core\Service\ContextService;

class ActivityLogService
{
    protected $logger;
    protected $em;
    protected $contextService;
    protected $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, ContextService $contextService)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->contextService = $contextService;
        $this->repository = $this->em->getRepository(ActivityLog::class);
    }

    /**
     * @param array $filters
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function getAll(array $filters, int $page, int $limit)
    {
        $entities = $this->repository->getAll($this->contextService->getDomain(), $filters, $limit, ($page - 1) * $limit);

        $total = $this->repository->getAllCount($this->contextService->getDomain(), $filters);

        return [
            'data' => $entities,
            'total' => $total
        ];
    }

    public function getUsers()
    {
        return $this->repository->getAllUsers();
    }

    public function getModels()
    {
        return $this->repository->getAllModels();
    }
}
