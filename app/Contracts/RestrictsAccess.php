<?php

namespace Trackit\Contracts;

interface RestrictsAccess
{
    /**
     * Check if a user can perform a given action
     * through the model.
     *
     * @param  string  $action
     * @param  \Trackit\Models\User  $user
     * @return boolean
     */
    public function allowsActionFrom($action, $user);
}
