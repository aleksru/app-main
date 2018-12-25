<?php

namespace App\Logging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class LoggerService
{
    private $originModel;
    private $type;
    private $customChanges = array();

    const TYPE_SET = 'Добавлено';
    const TYPE_UNSET = 'Удалено';
    const TYPE_UPDATE = 'Обновлено';

    /**
     * @param Model $model
     * @return $this
     */
    public function setOriginModel(Model $model)
    {
        $this->originModel = $model;

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setCustomChanges(array $changes)
    {
        $this->customChanges = $changes;

        return $this;
    }

    /**
     * Сохранение в базу
     */
    public function saveLog()
    {
        $this->originModel->logs()->create([
            'user_id' => Auth::user() ? Auth::user()->id : null,
            'actions' => $this->getStrChangesOnModel(),
            'type' => $this->type
        ]);

    }

    /**
     * @return string
     */
    private function getStrChangesOnModel()
    {
        if (! empty($this->customChanges)) {
            return json_encode($this->customChanges, true);
        }

        $changes = empty($this->originModel->getChanges()) ? $this->originModel->getAttributes() : $this->originModel->getChanges();

        return json_encode($changes, true);
    }

}