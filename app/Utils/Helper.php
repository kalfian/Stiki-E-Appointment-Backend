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

if (!function_exists('generate_code')) {
    function generate_code($prefix, int $number, int $length = 3) {
        return $prefix.str_pad($number, $length, 0, STR_PAD_LEFT);
    }
}

if (!function_exists('password_generator')) {
    function password_generator($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        return $password;
    }
}
