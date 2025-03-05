<?php

namespace App\Http\Controllers;

use App\Models\Recipient;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
    public function index()
    {
        return response()->json(Recipient::all());
    }

    public function store(Request $request)
    {
        $recipient = Recipient::create($request->all());
        return response()->json($recipient, 201);
    }

    public function show(Recipient $recipient)
    {
        return response()->json($recipient);
    }

    public function update(Request $request, Recipient $recipient)
    {
        $recipient->update($request->all());
        return response()->json($recipient);
    }

    public function destroy(Recipient $recipient)
    {
        $recipient->delete();
        return response()->json(null, 204);
    }
}


