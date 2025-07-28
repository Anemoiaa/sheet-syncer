<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class FetchController extends Controller
{
    public function __invoke(int $count): Response
    {
        Artisan::call('google-sheet:print', ['--count' => $count]);

        $output = Artisan::output();

        return response("<pre>{$output}</pre>");
    }
}
