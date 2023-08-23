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

if (! function_exists('setting')) {
    function setting() {
        return App\Models\Setting::class;
    }
}

if (!function_exists('isDebug')) {
    function isDebug() {
        return config('app.debug') === true;
    }
}

if (!function_exists('generateCode')) {
    function generateCode($prefix, int $number, int $length = 3) {
        return $prefix.str_pad($number, $length, 0, STR_PAD_LEFT);
    }
}

if (!function_exists('passwordGenerator')) {
    function passwordGenerator($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        return $password;
    }
}

if (!function_exists('isRoute')) {
    function isRoute($name, $globalName, $useGlobal = true) {
        if ($useGlobal) {
            return request()->route()->getName() == $name || str_contains(request()->route()->getName(), $globalName);
        }
        return request()->routeIs($name);
    }
}

if (!function_exists('translateMajor')) {
    function translateMajor($major) {
        switch($major) {
            case 'ti':
                return 'Teknik Informatika';
            case 'si':
                return 'Sistem Informasi';
            case 'mi':
                return 'Manajemen Informatika';
            case 'dkv':
                return 'Desain Komunikasi Visual';
            default:
                return '-';
        }
    }
}

if (!function_exists('translateGender')) {
    function translateGender($gender) {
        switch($gender) {
            case 0:
                return 'Male';
            case 1:
                return 'Female';
            default:
                return '-';
        }
    }
}

if (!function_exists('carbon_format')) {
    function carbon_format($date, $format = 'd M Y') {
        return \Carbon\Carbon::parse($date)->format($format);
    }
}
