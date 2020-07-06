<?php

namespace App;

use App\Models\ClientPhone;
use App\Models\Traits\HasSms;
use App\Repositories\OrderStatusRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use Notifiable, HasSms;

    protected $guarded = ['id'];

    protected $casts = [
        'is_black_list' => 'boolean',
    ];

    /**
     * Получаение звонков клиента
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(ClientCall::class);
    }

    /**
     * Получение заказов клиента
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Получение клиента по номеру телефона
     * @param $query
     * @param $phone
     */
    public function scopeGetOnPhone($query, $phone)
    {
        return $query->where('phone', preg_replace('/[^0-9]/', '', $phone));
    }

    /**
     * Оставляем только цифры в номере телефона
     *
     * @param  string $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        if (strlen($value) === 10) {
            $value = '7' . $value;
        }
        $this->attributes['phone'] = substr_replace($value, '7', 0, 1);
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function preparePhone($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        if (strlen($value) === 10) {
            $value = '7' . $value;
        }

        return substr_replace($value, '7', 0, 1);
    }

    /**
     * Логи
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logs()
    {
        return $this->morphMany(Log::class, 'logtable');
    }

    /**
     * Доп номера телефонов
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function additionalPhones()
    {
        return $this->hasMany(ClientPhone::class);
    }

    /**
     * Получить все номера клиента
     *
     * @return Collection
     */
    public function getAllPhonesAttribute()
    {
        return $this->additionalPhones->pluck('phone')->push($this->phone);
    }

    /**
     * Получить все доп номера клиента
     *
     * @return Collection
     */
    public function getAllAdditionalPhonesAttribute()
    {
        $phones = '';

        $this->additionalPhones->each(function ($item, $key) use (&$phones) {
            $phones = $phones . ' ' . $item->phone;
        });

        return $phones;
    }

    /**
     * Получение клиента по номеру телефона
     *
     * @param string $phone
     * @return Model|null
     */
    public static function getClientByPhone(string $phone)
    {
        $phone = self::preparePhone($phone);
        $client = Client::getOnPhone($phone)->first();

        if (!$client) {
            $client = ClientPhone::findByPhone($phone)->first();
            $client ? $client = $client->client : null;
        }

        return $client;
    }

    public static function getOrCreateClientFromPhone(string $phone, string $name = 'Не указано'): self
    {
        $client = self::getClientByPhone($phone);
        if( ! $client ){
            $client = self::create([
                'phone' => $phone,
                'name'  => $name
            ]);
        }

        return $client;
    }

    /**
     * @param int $statusId
     * @return int
     */
    public function getOrdersCountForStatus(int $statusId): int
    {
        return $this->orders()->where('status_id', $statusId)->count();
    }

    /**
     * @return int
     */
    public function getOrdersCountForStatusNew():int
    {
        return $this->getOrdersCountForStatus(app(OrderStatusRepository::class)->getIdStatusNew());
    }

    /**
     * @return int
     */
    public function getOrdersCountForStatusConfirmed():int
    {
        return $this->getOrdersCountForStatus(app(OrderStatusRepository::class)->getIdsStatusConfirmed());
    }

    /**
     * @return bool
     */
    public function isComplaining(): bool
    {

        return $this->storeComplaints()->count() > 0;
    }

    /**
     * @return bool
     */
    public function isSuccessSales(): bool
    {
        return $this->storeSuccess()->count() > 0;
    }

    /**
     * @return bool
     */
    public function isLoyal(): bool
    {
        return $this->getOrdersCountForStatusConfirmed() > 1 ? $this->isSuccessSales() && ! $this->isComplaining() : false;
    }

    public function storeInfo()
    {
        return $this->belongsToMany(
            Store::class,
            'client_store_info',
            'client_id',
            'store_id'
        )->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function storeComplaints()
    {
        return $this->storeInfo()->wherePivot('is_complaint', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function storeSuccess()
    {
        return $this->storeInfo()->wherePivot('is_success', 1);
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isStoreComplaint(int $storeId) : bool
    {
        return $this->storeComplaints->pluck('id')->contains($storeId);
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isStoreSuccess(int $storeId) : bool
    {
        return $this->storeSuccess->pluck('id')->contains($storeId);
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isLoyalStore(int $storeId) : bool
    {
        return $this->isStoreSuccess($storeId) && ! $this->isStoreComplaint($storeId);
    }

    /**
     * @param int $storeId
     */
    public function addComplaintStore(int $storeId)
    {
        $this->storeInfo()->syncWithoutDetaching([$storeId => ['is_complaint' => 1]]);
    }

    /**
     * @param int $storeId
     */
    public function addSuccessStore(int $storeId)
    {
        $this->storeInfo()->syncWithoutDetaching([$storeId => ['is_success' => 1]]);
    }

    /**
     * @return int
     */
    public function countOrders() : int
    {
        return $this->orders()->count();
    }

    /**
     * @param int $storeId
     * @return int
     */
    public function countOrdersInStore(int $storeId) : int
    {
        return $this->orders()->where('store_id', $storeId)->count();
    }
}
