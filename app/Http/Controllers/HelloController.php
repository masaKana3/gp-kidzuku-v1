<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class HelloController extends Controller
{
    // ⭐️⭐️⭐️以下3行をコピペ
    public function hello()
    {
        return view('greeting');
    }
}
