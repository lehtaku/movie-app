<?php

namespace App\Http\Controllers;

use App\Mail\DemoEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function send() {
        $target = 'akulehtonen@hotmail.com';

        Mail::to($target)->send(new DemoEmail());
    }
}
