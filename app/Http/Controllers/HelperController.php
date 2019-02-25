<?php

namespace App\Http\Controllers;

use App\Models\Location;

class HelperController extends Controller
{
    public static function getCurrentLocation()
    {
        return Location::findOrFail('acc39f12-68bb-4c9a-8791-d52ab49fcd12');
    }
}
