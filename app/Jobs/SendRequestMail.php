<?php

namespace App\Jobs;

use App\Mail\SendRequest1;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRequestMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
//    protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
//        $this->request=$request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $email = new SendRequest1();
        Mail::to($this->details['email'])->send($email);

//          Mail::to('fahed@gmail.com')->send(new SendRequest1('test'));

//        $front=collect($this->request);
//         Mail::send('email.sendmail',compact('front'), function ($message) {
//            $message->to('fahed8592@gmail.com', 'laravel')
//                ->subject('store register request');
//        });
    }
}
