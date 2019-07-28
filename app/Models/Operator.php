<?php

namespace App\Models;

use App\ClientCall;
use App\Order;
use App\Repositories\CallsRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $guarded = ['id'];

    /**
     * Получение оператора по sip
     *
     * @param string $login
     * @return Operator|null
     */
    public static function getOperatorBySipLogin(string $login)
    {
        return Operator::where('sip_login', $login)->first();
    }

    /**
     * Звонки оператора
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(ClientCall::class);
    }

    /**
     * Заказы
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getCallBacksForDate(Carbon $dateFrom, ?Carbon $dateTo = null)
    {
        $calls = app(CallsRepository::class)->getMissedCallBacksInTime($dateFrom, $dateTo);
        $calls = $calls->reject(function ($value, $key) {
            return $value->operator_id_outgoing != $this->id;
        });

        return $calls;
    }
}
