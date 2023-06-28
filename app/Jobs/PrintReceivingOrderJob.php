<?php

namespace Modules\Receiving\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Receiving\Models\Receiving;
use Modules\Receiving\Services\ReceivingPrintService;

class PrintReceivingOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected  $receiving;

    public function __construct($receiving)
    {
        $this->receiving = $receiving;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new ReceivingPrintService();
        $service->printOrder($this->receiving);

    }
}
