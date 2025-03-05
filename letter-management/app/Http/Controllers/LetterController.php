<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    public function index()
    {
        return response()->json(Letter::all());
    }

    public function store(Request $request)
    {
        $letter = Letter::create($request->all());
        return response()->json($letter, 201);
    }

    public function show(Letter $letter)
    {
        return response()->json($letter);
    }

    public function update(Request $request, Letter $letter)
    {
        $letter->update($request->all());
        return response()->json($letter);
    }

    public function destroy(Letter $letter)
    {
        $letter->delete();
        return response()->json(null, 204);
    }
}
