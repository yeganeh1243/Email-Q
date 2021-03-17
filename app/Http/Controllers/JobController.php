<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Jobs\SendEmail;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Http\Request;


class JobController extends Controller
{
    public function index()
    {

        return View('email');
    }
    public function send(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required|max:255',
            'body' => 'required',
            'email' => 'required|email',
        ]);
        if($validatedData){
            $details=[
                "email"=>$request->email,
                "subject"=>$request->subject,
                "body"=>$request->body,
                "file"=>$request->file('file')
            ];
            $emailJob = (new SendWelcomeEmail($details))->delay(Carbon::now()->addMinutes(5));
            dispatch($emailJob);
            return redirect()->route('api.index');
        }

    }
}
