<?php

namespace WS\Core\Twig\Extension;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Environment;
use Twig\Extension\AbstractExtension;

class CRUDExtension extends AbstractExtension
{
    private $requestStack;
    private $generator;
    private $translator;

    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $generator, TranslatorInterface $translator)
    {
        $this->requestStack = $requestStack;
        $this->generator = $generator;
        $this->translator = $translator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('ws_cms_path', [$this, 'getPath']),
            new TwigFunction('ws_cms_crud_list_is_date', [$this, 'listIsDate']),
            new TwigFunction('ws_cms_crud_list_filter', [$this, 'listFilter'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    public function getPath($name, $parameters = [], $relative = false)
    {
        $request = $this->requestStack->getCurrentRequest();

        $parameters = array_merge(
            $this->generator->getContextParams($name, $request->attributes->get('_route_params')),
            $parameters
        );

        return $this->generator->generate($name, $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    public function listIsDate(?\DateTimeInterface $dateTime)
    {
        if ($dateTime instanceof \DateTimeInterface) {
            return $dateTime->format($this->translator->trans('date_hour_format', [], 'ws_cms'));
        }

        return '-';
    }

    public function listFilter(Environment $environment, $filter, $value)
    {
        $twigFilter = $environment->getFilter($filter);
        if ($twigFilter instanceof TwigFilter) {
            $filteredValue = call_user_func($twigFilter->getCallable(), $value);

            $safeContext = $twigFilter->getSafe(new \Twig\Node\Node());
            if (!is_array($safeContext) || !in_array('html', $safeContext)) {
                /** @var \Twig\TwigFilter $twigFilter */
                $escapeFilter = $environment->getFilter('escape');
                $filteredValue = call_user_func($escapeFilter->getCallable(), $environment, $filteredValue);
            }

            return $filteredValue;
        }

        return $value;
    }
}
