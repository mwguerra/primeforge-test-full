<?php

use App\Jobs\ProcessDemoJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('/storage', function () {
    try {
        $files = collect(Storage::disk('s3')->allFiles())->map(fn ($file) => [
            'name' => $file,
            'url' => Storage::disk('s3')->url($file),
            'size' => Storage::disk('s3')->size($file),
        ])->all();
    } catch (\Throwable) {
        $files = [];
    }

    return view('storage', [
        'files' => $files,
        'disk' => config('filesystems.default'),
        'endpoint' => config('filesystems.disks.s3.endpoint'),
        'bucket' => config('filesystems.disks.s3.bucket'),
    ]);
});

Route::post('/upload', function () {
    $file = request()->file('file');
    if (! $file) {
        return back()->with('error', 'No file selected.');
    }

    $path = Storage::disk('s3')->putFile('uploads', $file);

    return back()->with('success', "File uploaded: {$path}");
});

Route::delete('/files/{path}', function (string $path) {
    Storage::disk('s3')->delete($path);

    return back()->with('success', "Deleted: {$path}");
})->where('path', '.*');
