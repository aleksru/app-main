<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ['status', 'description', 'color'];

    /**
     * Префиксы статусов
     * @var string
     */
    const STATUS_NEW_PREFIX = 'новый';

    const STATUS_CONFIRM_PREFIX = 'подтвержден';

    const STATUS_CALLBACK_PREFIX = 'перезвон';

    const STATUS_MISSED_PREFIX = 'недозвон';

    const STATUS_DENIAL_PREFIX = 'отказ';

    const STATUS_SPAM_PREFIX = 'спам';

    const STATUS_PAYMENT_PREFIX = 'выкуп';

    const STATUS_COMPLAINT_PREFIX = 'претензия';

    /**
     * Цвет по умолчанию
     *
     * @param $color
     * @return string
     */
    public function getColorAttribute($color)
    {
        if(! $color) {
            return 'default';
        }

        return $color;
    }

    /**
     * id Статусов для склада
     *
     * @return Collection
     */
    public static function getIdsStatusesForStock():Collection
    {
       return OrderStatus::where('status', 'like', '%' . StockUser::STATUS_PREFIX . '%' )->pluck('id');
    }

    /**
     * Получение ид статуса нового заказа
     *
     * @return string|null
     */
    public static function getIdStatusNew()
    {
       $status = OrderStatus::where('status', 'like', '%' . self::STATUS_NEW_PREFIX . '%' )->first();

       if(!$status) {
           return null;
       }

       return $status->id;
    }

    /**
     *  Получение ид статуса подтвержден
     *
     * @return int|null
     */
    public static function getIdStatusConfirm()
    {
        $status = OrderStatus::where('status', 'like', '%' . self::STATUS_CONFIRM_PREFIX . '%' )->first();

        if(!$status) {
            return null;
        }

        return $status->id;
    }

    /**
     * id Статусов для логистика
     *
     * @return Collection
     */
    public static function getIdsStatusesForLogistic():Collection
    {
        return OrderStatus::where('status', 'like', '%' . Logist::STATUS_PREFIX . '%' )->pluck('id');
    }

    public static function getStatusSpam(): ?self
    {
        return self::getStatusForType(self::STATUS_SPAM_PREFIX);
    }

    /**
     * Ид для типа
     *
     * @param string $type
     * @return int|null
     */
    public static function getIdStatusForType(string $type)
    {
        $status = OrderStatus::where('status', 'like', '%' . $type . '%' )->first();

        if(!$status) {
            return null;
        }

        return $status->id;
    }

    /**
     * @param string $type
     * @return null
     */
    public static function getStatusForType(string $type)
    {
        return OrderStatus::where('status', 'like', '%' . $type . '%' )->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subStatuses()
    {
        return $this->belongsToMany(OtherStatus::class, 'statuses_other_statuses', 'order_status_id')->typeSubStatuses();
    }

    /**
     * @return int|null
     */
    public static function getIdsStatusComplaining()
    {
        return self::getIdStatusForType(self::STATUS_COMPLAINT_PREFIX);
    }
}
