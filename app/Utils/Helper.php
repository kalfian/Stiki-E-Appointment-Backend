<?php

if (! function_exists('referenceStatus')) {
    function referenceStatus() {
        $referenceStatus = new App\Models\ReferenceStatus();
        return $referenceStatus;
    }
}

if (! function_exists('male')) {
    function male() {
        return 0;
    }
}

if (!function_exists('female')) {
    function female() {
        return 1;
    }
}

if (!function_exists('role')) {
    function role() {
        $role = new App\Utils\Role();
        return $role;
    }
}


if (!function_exists('is_debug')) {
    function is_debug() {
        return config('app.debug') === true;
    }
}
