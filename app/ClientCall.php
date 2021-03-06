<?php

namespace App;

use App\Enums\MangoCallEnums;
use App\Models\Operator;
use Illuminate\Database\Eloquent\Model;

class ClientCall extends Model
{
    protected $guarded = ['id'];

    /**
     * константа
     * тип звонка
     * входящий
     */
    const incomingCall = 'INCOMING';

    /**
     * константа
     * тип звонка
     * исходящий
     */
    const outgoingCall = 'OUTGOING';

    protected $casts = [
        'is_first' => 'boolean',
    ];

    /**
     * Получение клиента
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function isReclamation(): bool
    {
        if($this->isExtensionReclamations()){
            return true;
        }
        if($this->client && $this->store_id){
            return $this->client->isStoreComplaint($this->store_id);
        }

        return false;
    }

    public function isExtensionReclamations(): bool
    {
        if( ! $this->extension ){
            return false;
        }
        return in_array((int)$this->extension, MangoCallEnums::COMPLAINT_NUMBERS);
    }

    /**
     * Оператор
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * Возвращает мазагин
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Получаем тип звонка
     * @return string
     */

    public function getTypeCallAttribute()
    {
        if ($this->type === self::incomingCall){
            return 'Входящий';
        }
        if ($this->type === self::outgoingCall) {
            return 'Исходящий';
        }

        return 'Не определен';
    }
    /**
     * @return bool
     */
    public function isOutgoing(): bool
    {
        return $this->type === self::outgoingCall;
    }

    /**
     * @return bool
     */
    public function isIncoming(): bool
    {
        return $this->type === self::incomingCall;
    }

    /**
     * @return bool
     */
    public function isMissed(): bool
    {
        return (int)$this->status_call === MangoCallEnums::CALL_RESULT_MISSED;
    }

    public function isSuccess(): bool
    {
        return (int)$this->status_call === MangoCallEnums::CALL_RESULT_SUCCESS;
    }

    /**
     * Получение последнего звонка для номера
     *
     * @param $number
     * @return Model|null
     */
    public static function getLastCallForNumber($number)
    {
        return ClientCall::where('from_number', $number)
                            ->where('type', self::incomingCall)
                            ->orderBy('created_at', 'desc')
                            ->first();
    }

    /**
     * Получение имени клиента
     *
     * @return string|null
     */
    public function getClientNameAttribute()
    {
        return $this->client ? $this->client->name : null;
    }

    /**
     * Получение названия магазина
     *
     * @return string|null
     */
    public function getStoreNameAttribute()
    {
        return $this->store ? $this->store->name : null;
    }

    /**
     * Получение хеш для столбца hash
     *
     * @param array $params
     * @return md5|string
     */
    public static function makeHash(array $params)
    {
        return md5(implode('', $params));
    }

    /**
     * Получение звонка по хеш
     *
     * @param string $hash
     * @return ClientCall|null
     */
    public static function getCallByHash(string $hash)
    {
        return ClientCall::where('hash', $hash)->first();
    }


    /**
     * @param $value
     */
    public function setOperatorTextAttribute($value)
    {
        $resExp = explode(':', $value);

        $this->attributes['operator_text'] =  count($resExp) > 1 ? $resExp[1] : $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function missedCall()
    {
        return $this->hasOne(MissedCall::class);
    }
}
