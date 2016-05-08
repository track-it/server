<?php

namespace Trackit\Contracts;

interface RestrictsAccess
{
    /**
     *
     */
    public function allowsActionFrom($action, $user);
}
