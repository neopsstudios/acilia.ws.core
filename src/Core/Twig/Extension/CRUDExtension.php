<?php

namespace WS\Core\Twig\Extension;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class CRUDExtension extends AbstractExtension
{
    protected $administratorService;
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('ws_cms_crud_list_is_date', [$this, 'listIsDate']),
            new TwigFunction('ws_cms_crud_list_filter', [$this, 'listFilter'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    public function listIsDate(?\DateTimeInterface $dateTime)
    {
        if ($dateTime instanceof \DateTimeInterface) {
            return $dateTime->format($this->translator->trans('date_hour_format', [], 'ws_cms'));
        }

        return '-';
    }

    public function listFilter(\Twig_Environment $environment, $filter, $value)
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
