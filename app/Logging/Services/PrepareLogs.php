<?php

namespace App\Logging\Services;

use App\Logging\Services\Classes\PrepareData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


class PrepareLogs
{
    private $logs;
    private $actions = [];

    /**
     * Выполнение расшифровки логов
     *
     * @return void
     */
    public function prepare()
    {
        foreach ($this->logs as $log) {
            $dataColumns = PrepareData::getNavigationColumns($log->logtable_type);

            if($dataColumns) {
                $this->actions = json_decode($log->actions, true);

                if (!$this->actions) {
                    continue;
                }

                $this->getColumnsText($dataColumns['colums']);

                $model = $this->getModel($log->logtable_id, $log->logtable_type);

                if($model) {
                    $this->getRelationsText($model, $dataColumns['relationsColums']);
                }

                $log->actions = $this->prepareArrayToString($this->actions);
                $log->status = 1;
                $log->save();
            }
        }
    }

    /**
     * Получить коллекцию логов
     *
     * @return mixed
     */
    public function getLogs ()
    {
        return $this->logs;
    }

    /**
     * Установить логи
     *
     * @param Collection $logs
     */
    public function setLogs (Collection $logs)
    {
        $this->logs = $logs;
    }

    /**
     * Найти модель
     *
     * @param int $id
     * @param string $entity
     * @return mixed
     */
    public function getModel (int $id, string $entity)
    {
        return $entity::find($id);
    }

    /**
     * Объединить в строку массив во ключ->значение
     *
     * @param array $arr
     * @return string
     */
    protected function prepareArrayToString(array $arr)
    {
        $string = '';

        foreach($arr as $key => $value) {
            if(is_array ($value)){
                $value = implode('|', $value);
            }
            $string = $string.', '.$key.': '.$value;
        }

        return $string;
    }

    /**
     * Расшифрока связных колонок
     *
     * @param Model $model
     * @param array $columns
     */
    protected function getRelationsText(Model $model, array $columns)
    {
        foreach($this->actions as $key => $action) {
            if(array_key_exists($key, $columns)) {
                $relation = $model->{$columns[$key]['relation']};

                if($relation) {
                    $this->actions[$columns[$key]['name']] = $relation->{$columns[$key]['column']};
                    unset($this->actions[$key]);
                }else{
                    $this->actions[$columns[$key]['name']] = 'Удалено';
                    unset($this->actions[$key]);
                }
            }
        }
    }

    /**
     * Расшифровка колонок
     *
     * @param array $columns
     */
    protected function getColumnsText(array $columns)
    {
        foreach($this->actions as $key => $action) {
            if(array_key_exists($key, $columns)) {
                $this->actions[$columns[$key]] = $this->actions[$key];
                unset($this->actions[$key]);
            }
        }
    }

}