<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;

class Debug extends Controller
{
    function debug() {
        echo Hash::make('admin');
    }
}
