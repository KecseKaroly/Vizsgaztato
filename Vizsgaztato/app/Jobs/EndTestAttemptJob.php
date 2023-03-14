<?php

namespace App\Jobs;

use App\Actions\StoreTestAttempt;
use App\Events\TestEnded;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EndTestAttemptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $test;
    /**
     * Create a new job instance.
     * @param $test
     * @return void
     */
    public function __construct($test)
    {
        $this->test = $test;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $attempt = (new StoreTestAttempt())->store($this->test);
        if($attempt)
            event(new TestEnded($attempt));
    }
}
