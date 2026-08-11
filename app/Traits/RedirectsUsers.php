<?php

namespace app\Traits;

/**
 * Trait RedirectsUsers
 *
 * Determines post-authentication redirect paths including:
 * - Resolving redirect paths after login
 * - Supporting custom redirectTo methods and properties
 * - Providing default admin dashboard redirect
 *
 * @package app\Traits
 */
trait RedirectsUsers
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/admin/dashboard';
    }
}
