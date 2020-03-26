<?php


namespace App\Enums;


class TextTypeEnums
{
    const ROOT = 'TEXT';
    const WARRANTY = 'WARRANTY';
    const VOUCHER = 'VOUCHER';
    const DELIVERY = 'DELIVERY';

    const WARRANTY_FULL = self::ROOT . '.' . self::WARRANTY;
    const VOUCHER_FULL = self::ROOT . '.' . self::VOUCHER;
    const DELIVERY_FULL = self::ROOT . '.' . self::DELIVERY;
}