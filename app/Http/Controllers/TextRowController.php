<?php

namespace App\Http\Controllers;

use App\Models\TextRow;
use Illuminate\View\View;

class TextRowController extends Controller
{
    public function index(): View
    {
        return view('welcome', [
            'textRows' => TextRow::all()
        ]);
    }
}
