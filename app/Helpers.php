<?php

namespace App;

use Illuminate\Support\Str;

class Helpers{
    public static function getSlug($string){
        return Str::slug($string);
    }
}