<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $guarded = ['id'];

    /**
     * Модели логирования
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function logtable()
    {
        return $this->morphTo();
    }

    /**
     * Пользователь
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Не обработанные логи
     *
     * @param $query
     * @return mixed
     */
    public function scopeNoPrepared($query)
    {
        return $query->where('status', 0);
    }
}
