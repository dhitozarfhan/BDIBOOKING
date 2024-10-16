<?php

namespace App\Http\Controllers;

use App\Models\QuestionSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function question()
    {
        return view('information.question');
    }

    public function submit(Request $request)
    {
        $validator = Validator::class($request->all(), [
            'g-recaptcha-response' => 'required|captcha',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!function_exists('generate_captcha')) {
            function generate_captcha()
            {
                // Ganti dengan logika untuk menghasilkan captcha
                return '<img src="path_to_your_captcha_image" alt="captcha">';
            }
        }

        QuestionSubmission::class($request->only('subject', 'content', 'name', 'mobile', 'email'));

        return redirect()->back()->with('message', 'Form submitted succesfully!');
    }
}
