<?php

namespace App;

use App\Enums\FileStatusesEnums;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function priceList()
    {
        return $this->hasOne(PriceType::class, 'id', 'price_list_id');
    }

    public function setProcessing()
    {
        $this->status = FileStatusesEnums::PROCESS;
        $this->save();
    }

    public function setProcessed()
    {
        $this->status = FileStatusesEnums::SUCCESS;
        $this->save();
    }

    public function setError()
    {
        $this->status = FileStatusesEnums::ERROR;
        $this->save();
    }

    public function setCountProcessed(int $count)
    {
        $this->count_processed = $count;
        $this->save();
    }
}
