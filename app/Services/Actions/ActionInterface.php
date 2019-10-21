<?php


namespace App\Services\Actions;

use App\Services\Mango\Commands\SendSms;

interface ActionInterface
{
    public function isCheckAction() : bool;
    public function isCheckParams() : bool;
    public function getMessage() : SendSms;
    public static function getNameAction() : String;
}