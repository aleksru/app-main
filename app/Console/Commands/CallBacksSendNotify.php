<?php

namespace App\Console\Commands;

use App\Enums\UserGroupsEnums;
use App\Models\UserGroup;
use App\Notifications\ClientCallBack;
use App\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CallBacksSendNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:call-backs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create notifications by call back';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orders = Order::query()->whereBetween('communication_time', [
            Carbon::now(),
            Carbon::now()->addMinutes(5)
        ])->get();
        foreach ($orders as $order) {
            $user = $order->operator ? $order->operator->user : null;
            if ($user && $user->isOnline()) {
                $user->notify(new ClientCallBack($order));
            } else if ($group = UserGroup::where(['name' => UserGroupsEnums::OPERATOR])->first()){
                $group->notify(new ClientCallBack($order));
            }
        }
    }
}
