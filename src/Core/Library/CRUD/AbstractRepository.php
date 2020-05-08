<?php

namespace WS\Core\Library\CRUD;

use Doctrine\ORM\NoResultException;
use WS\Core\Entity\Domain;
use WS\Core\Library\Domain\DomainRepositoryTrait;
use WS\Core\Service\ContextService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository
{
    use DomainRepositoryTrait;

    protected $contextService;

    public function __construct(ContextService $contextService, ManagerRegistry $registry)
    {
        $this->contextService = $contextService;

        parent::__construct($registry, $this->getEntityClass());
    }

    abstract public function getEntityClass();

    abstract public function getFilterFields();

    public function processFilterExtended(QueryBuilder $qb, ?array $filterExtendedData)
    {
    }

    /**
     * @param Domain $domain
     * @param string $filter
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return mixed
     */
    public function getAll(Domain $domain, ?string $filter, ?array $filterExtendedData, array $orderBy = null, int $limit = null, int $offset = null)
    {
        $alias = 't';
        $qb = $this->createQueryBuilder($alias);

        $this->setFilter($alias, $qb, $filter);

        if ($orderBy && count($orderBy)) {
            foreach ($orderBy as $field => $dir) {
                $qb->orderBy(sprintf('%s.%s', $alias, $field), $dir);
            }
        }

        if (isset($limit) && isset($offset)) {
            $qb->setFirstResult($offset);
            $qb->setMaxResults($limit);
        }

        $this->processFilterExtended($qb, $filterExtendedData);

        $this->setDomainRestriction($alias, $qb, $domain);

        return $qb->getQuery()->execute();
    }

    public function getAvailableByIds(Domain $domain, array $ids): array
    {
        $alias = 't';
        $qb = $this->createQueryBuilder($alias)
            ->where(sprintf('%s.id IN (:ids)', $alias))
            ->setParameter('ids', $ids);

        $this->setDomainRestriction($alias, $qb, $domain);

        return $qb->getQuery()->execute();
    }

    /**
     * @param Domain $domain
     * @param string|null $filter
     * @return int
     */
    public function getAllCount(Domain $domain, ?string $filter, ?array $filterExtendedData)
    {
        $alias = 't';

        $qb = $this->createQueryBuilder($alias)->select(sprintf(sprintf('count(%s.id)', $alias)));

        $this->setFilter($alias, $qb, $filter);

        $this->processFilterExtended($qb, $filterExtendedData);

        $this->setDomainRestriction($alias, $qb, $domain);

        try {
            return $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        } catch (NoResultException $e) {
            return 0;
        }
    }

    /**
     * @param string $alias
     * @param QueryBuilder $qb
     * @param string|null $filter
     */
    protected function setFilter(string $alias, QueryBuilder $qb, ?string $filter)
    {
        if (!$filter) {
            return;
        }

        foreach ($this->getFilterFields() as $field) {
            $qb->orWhere(sprintf('%s LIKE :%s_filter', sprintf('%s.%s', $alias, $field), $field));
            $qb->setParameter(sprintf('%s_filter', $field), sprintf('%%%s%%', $filter));
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param string $alias
     * @param array $filters
     */
    protected function setFilters(string $alias, QueryBuilder $qb, array $filters)
    {
        foreach ($filters as $field => $value) {
            $qb->andWhere(sprintf('%s LIKE :%s_filter', sprintf('%s.%s', $alias, $field), $field));
            $qb->setParameter(sprintf('%s_filter', $field), sprintf('%%%s%%', $value));
        }
    }

    /**
     * @param array $ids
     */
    public function batchDelete(array $ids)
    {
        $alias = 't';

        $qb = $this->createQueryBuilder($alias)
            ->delete($this->getEntityClass(), $alias)
            ->where(sprintf('%s.id IN (:ids)', $alias))
            ->setParameter('ids', $ids);

        $qb->getQuery()->execute();
    }
}
