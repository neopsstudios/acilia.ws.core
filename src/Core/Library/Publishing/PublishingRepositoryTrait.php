<?php

namespace WS\Core\Library\Publishing;

use Doctrine\ORM\QueryBuilder;

trait PublishingRepositoryTrait
{
    protected function setPublishingRestriction(string $alias, QueryBuilder &$qb)
    {
        $qb
            ->andWhere(sprintf('%s.publishStatus = :status', $alias))
            ->andWhere(sprintf('%s.publishSince <= :today OR %s.publishSince IS NULL', $alias, $alias))
            ->andWhere(sprintf('%s.publishUntil >= :today OR %s.publishUntil IS NULL', $alias, $alias))
            ->setParameter('today', date('Y-m-d h:i:s'))
            ->setParameter('status', PublishingEntityInterface::STATUS_PUBLISHED);
    }
}
