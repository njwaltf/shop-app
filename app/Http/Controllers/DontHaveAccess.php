<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DontHaveAccess extends Controller
{
    public function index()
    {
        return 'you dont have access for this pages!';
    }
}
