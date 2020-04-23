<?php

namespace WS\Core\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use WS\Core\Twig\Tag\PageConfiguration\PageConfigurationTokenParser;

class PageConfigurationExtension extends AbstractExtension
{
    protected $title;
    protected $header;
    protected $subheader;
    protected $breadcrumbs;

    public function __construct()
    {
        $this->title = 'CMS';
        $this->header = 'CMS';
        $this->breadcrumbs = [];
    }

    public function getTokenParsers()
    {
        return [
            new PageConfigurationTokenParser(),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_title', [$this, 'getTitle']),
            new TwigFunction('get_header', [$this, 'getHeader']),
            new TwigFunction('get_subheader', [$this, 'getSubheader']),
            new TwigFunction('get_breadcrumbs', [$this, 'getBreadcrumbs']),
        ];
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getSubheader()
    {
        return $this->subheader;
    }

    public function getBreadcrumbs()
    {
        return $this->breadcrumbs;
    }

    public function configure($config)
    {
        if (isset($config['title'])) {
            $this->title = $config['title'];
        }

        if (isset($config['header'])) {
            $this->header = $config['header'];
        }

        if (isset($config['subheader'])) {
            $this->subheader = $config['subheader'];
        }

        if (isset($config['breadcrumbs']) && is_array($config['breadcrumbs'])) {
            $this->breadcrumbs = $config['breadcrumbs'];
        }
    }
}
