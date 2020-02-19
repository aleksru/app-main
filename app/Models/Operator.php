<?php

namespace App\Models;

use App\ClientCall;
use App\Order;
use App\Repositories\CallsRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Operator extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_disabled' => 'bool',
    ];

    /**
     * Получение оператора по sip
     *
     * @param string $login
     * @return Operator|null
     */
    public static function getOperatorBySipLogin(string $login)
    {
        $login = str_replace('sip:', '', $login);
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return ! $this->is_disabled;
    }

    /**
     * @return Builder
     */
    public function scopeIsActive($query)
    {
        return $query->where('is_disabled', 0);
    }

    public function getCountOrdersForStatus(int $idStatus)
    {
        return $this->orders->reduce(function ($prev, $cur) use ($idStatus){
            return $prev + ($cur->status_id == $idStatus ? 1 : 0);
        }, 0);
    }
}
