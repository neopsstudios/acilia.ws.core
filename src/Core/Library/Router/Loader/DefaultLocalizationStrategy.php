<?php

namespace WS\Core\Library\Router\Loader;

use WS\Core\Service\DomainService;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Doctrine\DBAL\DBALException;

class DefaultLocalizationStrategy implements LocalizationStrategyInterface
{
    protected $domains = null;
    protected $aliases = null;
    protected $aliasList = null;

    protected $domainService;

    public function __construct(DomainService $domainService)
    {
        $this->domainService = $domainService;
    }

    protected function getDomains() : array
    {
        if ($this->domains === null) {
            try {
                $domains = $this->domainService->getCanonicals();
            } catch (DBALException $e) {
                $domains = [];
            }

            foreach ($domains as $domain) {
                $this->domains[$domain->getLocale()] = $domain->getHost();
            }
        }

        return ($this->domains === null) ? [] : $this->domains;
    }

    protected function getAliases() : array
    {
        if ($this->aliases === null) {
            $domains = $this->domainService->getAliases();

            $this->aliases = [];
            foreach ($domains as $domain) {
                if (!isset($this->aliases[$domain->getParent()->getHost()])) {
                    $this->aliases[$domain->getParent()->getHost()] = [];
                }

                $this->aliases[$domain->getParent()->getHost()][] = $domain->getHost();
            }
        }

        return ($this->aliases === null) ? [] : $this->aliases;
    }

    protected function getAliasList() : array
    {
        if ($this->aliasList === null) {
            $domains = $this->domainService->getAliases();

            $this->aliasList = [];
            foreach ($domains as $domain) {
                $this->aliasList[$domain->getHost()] = $domain->getHost();
            }
        }

        return ($this->aliasList === null) ? [] : $this->aliasList;
    }

    public function getLocales() : array
    {
        return array_keys($this->getDomains());
    }

    public function getParameters(RequestContext $context) : array
    {
        $parameters = [];
        $aliasList = $this->getAliasList();
        $domains = $this->getDomains();
        $hosts = array_unique(array_values($domains));

        if (count($hosts) >= 1) {
            if (isset($aliasList[$context->getHost()])) {
                $parameters['ws_hosts'] = $context->getHost();
            }
        }

        return $parameters;
    }

    public function localize($locale, Route $route)
    {
        $aliases = $this->getAliases();
        $domains = $this->getDomains();
        $hosts = array_unique(array_values($domains));

        if (count($hosts) >= 1 && isset($domains[$locale])) {
            if (isset($aliases[$domains[$locale]])) {
                $host = implode('|', array_merge([$domains[$locale]], $aliases[$domains[$locale]]));
                $route->setRequirement('ws_hosts', $host);
                $route->setHost('{ws_hosts}');
            } else {
                $route->setHost($domains[$locale]);
            }
        }
    }
}
