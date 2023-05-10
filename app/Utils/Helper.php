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
