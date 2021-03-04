<?php

namespace WS\Core\Library\Domain;

use Doctrine\ORM\QueryBuilder;
use WS\Core\Entity\Domain;
use WS\Core\Library\Domain\DomainDependantInterface;

trait DomainRepositoryTrait
{
    /**
     * @param string $alias
     * @param QueryBuilder $qb
     * @param Domain $domain
     */
    protected function setDomainRestriction($alias, QueryBuilder $qb, Domain $domain)
    {
        if (in_array(DomainDependantInterface::class, class_implements($this->getClassName()))) {
            $qb->andWhere(sprintf('%s.domain = :domain', $alias));
            $qb->setParameter('domain', $domain);
        }
    }
}
