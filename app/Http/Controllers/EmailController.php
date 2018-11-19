<?php

namespace App\Http\Controllers;

use App\Mail\DemoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail(){
        $data = array('name'=>"Aku Lehtonen");

        Mail::send(['text'=>'mail'], $data, function($message) {
            $message->to('akulehtonen@hotmail.com', 'Aku Lehtonen')->subject
            ('Laravel Basic Testing Mail');
            $message->from('admin@endumx.com','Endumx');
        });
        echo "Basic Email Sent. Check your inbox.";
    }
}
