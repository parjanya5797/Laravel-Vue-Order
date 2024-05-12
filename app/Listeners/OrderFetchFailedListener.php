<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Events\OrderFetchFailed;
class OrderFetchFailedListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 5;

    /**
     * The name of the queue connection to use.
     *
     * @var string
     */
    public $connection = 'sync';
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderFetchFailed $event): void
    {
        try {
            $message = "Order fetch failed with status {$event->responseStatus} and body {$event->responseBody}";
        Mail::raw($message, function ($mail) use ($event) {
            $mail->to('admin@matat.com')
                 ->subject('Order Fetch Failure Notification');
        });
        } catch (\Throwable $th) {
           dd($th); //throw $th;
        }
    }
}
