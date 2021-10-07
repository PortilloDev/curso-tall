<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{

    public function all()
    {
        return view('subscriber.all');
    }


    public function verify(Subscriber $subscriber) 
    {
        if (!$subscriber->hasVerifiedEmail()) {
            $subscriber->markEmailAsVerified();
        }

        return redirect('/?verified=1');
    }
}
