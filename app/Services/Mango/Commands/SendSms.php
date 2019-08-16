<?php


namespace App\Services\Mango\Commands;


use Illuminate\Support\Str;

class SendSms
{
    /**
     * @var string
     */
    public $to_number;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $from_extension;

    /**
     * @var string
     */
    public $command_id;

    /**
     * @var string
     */
    public $sms_sender;

    /**
     * SendSms constructor.
     */
    public function __construct()
    {
        $this->command_id = Str::uuid();
    }

    /**
     * @param $phone
     * @return $this
     */
    public function phone($phone)
    {
        $this->to_number = $phone;

        return $this;
    }

    /**
     * @param $text
     * @return $this
     */
    public function text($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param $fromExtension
     * @return $this
     */
    public function fromExtension($fromExtension)
    {
        $this->from_extension = $fromExtension;

        return $this;
    }

    /**
     * @param $commandId
     * @return $this
     */
    public function commandId($commandId)
    {
        $this->command_id = $commandId;

        return $this;
    }

    /**
     * @param string $smsRender
     * @return $this
     */
    public function smsRender(string $smsRender)
    {
        $this->sms_sender = $smsRender;

        return $this;
    }
}