<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    public function question()
    {
        return view('information.question');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'name' => 'required|string|max:30',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:50',
        ]);

        $questionCode = strtoupper(Str::random(6));
        $category = Category::where('type', 'question')->first();
        $categoryId = $category ? $category->category_id : 7;

        $question = new Question([
            'question_code' => $questionCode,
            'category_id' => $categoryId,
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'] ?? null,
            'status' => 'C',
            'time_insert' => now(),
            'time_update' => now(),
            'time_publish' => now(),
        ]);

        $question->save();

        return redirect()->route('information.question')->with('msg', 'Pertanyaan anda telah dikirim!');
    }
}
