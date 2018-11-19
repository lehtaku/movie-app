<?php

namespace App\Http\Controllers;

use App\Mail\DemoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail() {
        $target = 'akulehtonen@hotmail.com';

        Mail::to($target)->send(new DemoMail());

        return "Email sent!";
    }
}
