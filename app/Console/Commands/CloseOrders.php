<?php


namespace App\Console\Commands;

use App\Models\OrderStatus;
use App\Order;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CloseOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close Orders';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(OrderRepository $orderRepository)
    {
//        $denialId = OrderStatus::getIdStatusForType(OrderStatus::STATUS_DENIAL_PREFIX);
//        $ids = $orderRepository->getOrdersForRecall(Carbon::today(), false)->pluck('id');
//        if($denialId && ! $ids->isEmpty()) {
//            Order::query()->whereIn('id', $ids)->update(['status_id' => $denialId]);
//        }
    }
}
