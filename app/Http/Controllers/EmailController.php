<?php

namespace App\Http\Controllers;

use App\Mail\DemoEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function send(Request $request) {
        $target = 'akulehtonen@hotmail.com';

        Mail::to($target)->send(new DemoEmail());

        return response()->json(['message' => 'Request completed']);
    }
}
