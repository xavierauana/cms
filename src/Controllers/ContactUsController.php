<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Mailables\ContactUsMailable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    protected $backUrl = null;

    public function send(Request $request) {
        if(strtolower($request->method()) !== 'post') {
            abort(403,
                  'Method Not Allowed');
        }
        if($email = setting('contact_us_email',
                            null)) {
            $mailable = (new ContactUsMailable($email,
                                               $request->except(['_token'])));
            Mail::to($email)->send($mailable);

            if($url = $this->backUrl) {
                return redirect($url)->withStatus(__('Thanks for your enquiry. We will get back to you soon.'));
            }

            return redirect()->back()
                             ->withStatus(__('Thanks for your enquiry. We will get back to you soon.'));
        }
    }
}
