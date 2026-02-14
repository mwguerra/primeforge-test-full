<?php

namespace App\Jobs;

use App\Events\DemoProgress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessDemoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            sleep(1);
            DemoProgress::dispatch("Step {$i}/5 complete", $i * 20);
        }

        DB::table('demo_results')->insert([
            'message' => 'Full demo job completed at ' . now()->toDateTimeString(),
            'created_at' => now(),
        ]);

        DemoProgress::dispatch('Job finished!', 100);
    }
}
