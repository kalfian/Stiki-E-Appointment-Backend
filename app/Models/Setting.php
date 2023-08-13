<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    const USER_DEFAULT_PASSWORD = "user_default_password";


    public static function getOrCreateByMeta($meta, $defaultValue = null)
    {
        $setting = Setting::where('meta', $meta)->first();
        if (!$setting) {
            $setting = new Setting();
            $setting->meta = $meta;
            $setting->value = $defaultValue;
            $setting->save();
        }

        return $setting;
    }

    public static function getDefaultPassword() {
        return Setting::getOrCreateByMeta(Setting::USER_DEFAULT_PASSWORD, '12345678');
    }

    public static function getDefaultPasswordValue() {
        return Setting::getDefaultPassword()->value;
    }

}
