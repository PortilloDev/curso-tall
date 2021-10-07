<?php

namespace App\Http\Livewire;

use App\Models\Subscriber;
use Illuminate\Auth\Notifications\VerifyEmail;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use NZTim\Mailchimp\Mailchimp;

class LandingPage extends Component
{
    
    public $email;
    public $showSubscribe = false;
    public $showSuccess = false;
    protected $rules = [
        'email' => 'required|email',
    ];
    protected $mailchimp;
    
     
    

    public function mount(Request $request) {
        if(request()->has('verified') && request()->verified == 1) {

           $this->showSuccess = true;
        }
    }

    public function subscribe() {

        $this->validate();
       /* $mailchimp = new Mailchimp(env('MC_KEY'));   
        $listId = "dec8544a41";
        $emailAddress = $this->email;
        
        if($mailchimp->check($listId, $emailAddress)) {
            return "El email $emailAddress ya está registrado";
        }
        $mailchimp->subscribe(
            $listId, 
            $emailAddress, 
            $merge = [
                'FNAME' => "Iván",
                'LNAME'=> "Portillo",
            ], 
            $confirm = false);
        return "El email $emailAddress se ha registrado correctamente";  */
        DB::transaction(function () {
            $subscriber = Subscriber::create([
                'email' => $this->email,
            ]);
             $notification = new VerifyEmail;

             $notification::createUrlUsing(function($notifiable) {
                return URL::temporarySignedRoute(
                    'subscribers.verify',
                    now()->addMinutes(30),
                    [
                        'subscriber' => $notifiable->getKey(),
                    ]
                );
             });

             $subscriber->notify($notification);
        }, $deadLockRetries = 5); 
            
            $this->reset('email');
            $this->showSubscribe = false;
            $this->showSuccess = true;
    }

    public function render()
    {
        return view('livewire.landing-page');
    }

    
}
