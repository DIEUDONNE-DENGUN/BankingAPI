<?php


namespace App\Customer\Transfers\Events;

use App\Customer\Transfers\Requests\TransactionTransferDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transactionDTO;

    public function __construct(TransactionTransferDTO $transactionTransferDTO)
    {
        $this->transactionDTO = $transactionTransferDTO;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
