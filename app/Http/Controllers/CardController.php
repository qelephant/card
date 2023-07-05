<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Feedback;
use App\Models\Method;
use App\Models\User;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $card = Card::with('strategy', 'principles' , 'questions', 'method', 'feedback', 'lessons')->findOrFail(request()->route('id'));

        $methods = $card['method'];
        $personalMethods = Method::where('user_id', 1)->get();
        $methods = $methods->concat($personalMethods);
        $feedback = $card['feedback'];

        $personalFeedback = Feedback::where('user_id', 1)->get();
        $feedback = $feedback->concat($personalFeedback);

        return view('cards.index', compact('card', 'methods', 'feedback'));
    }
}
