<?php


namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Parts;


use App\Enums\CorporateInfoEnums;

class CorporateInfo
{
    /**
     * @return array
     */
    public function getPart() : array
    {
        $res = [];
        $fields = app(CorporateInfoEnums::class)->getConstants();
        foreach ($fields as $field) {
            $res[$field['key']] = setting($field['key']);
        }

        return $res;
    }
}