<?php

namespace WS\Core\Library\CRUD;

use Symfony\Component\Form\AbstractType;

abstract class AbstractFilterType extends AbstractType
{

    public function getBlockPrefix()
    {
        return 'fe';
    }
}