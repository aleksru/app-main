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
}
