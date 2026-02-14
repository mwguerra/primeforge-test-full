<?php

use App\Jobs\ProcessDemoJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/demo', function () {
    return view('demo');
});

Route::post('/demo/start', function () {
    ProcessDemoJob::dispatch();
    return response()->json(['status' => 'Job dispatched']);
});

Route::get('/demo/results', function () {
    return response()->json(DB::table('demo_results')->orderByDesc('id')->limit(10)->get());
});
