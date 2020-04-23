<?php

namespace WS\Core\Library\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class SlugType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'ws_attr' => ['data-component' => 'ws_slug']
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
}
