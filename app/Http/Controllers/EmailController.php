<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail() {
        $message = "Hello from endumx";

        mail('akulehtonen@hotmail.com', 'New laravel message', $message);
    }
}
