<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberRequest;
use App\Models\Subscriber;
use App\Services\SubscriberServices;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class SubscribersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        return view('subscribers.index');
    }


    public function create(SubscriberRequest $request)
    {
        $subscriber = (new SubscriberServices())
            ->addNewSubscriber(
                $request->first_name,
                $request->last_name,
                $request->email);


        return redirect()
            ->back()
            ->withFlashSucess('Success!');


    }
}
