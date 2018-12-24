<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * Модели логирования
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function logtable()
    {
        return $this->morphTo();
    }
}
