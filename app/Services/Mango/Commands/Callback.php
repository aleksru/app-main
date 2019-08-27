<?php


namespace App\Services\Mango\Commands;


class Callback
{
    /**
     * Id command mango
     * @var mixed
     */
    public $command_id;

    /**
     * @var array
     */
    public $from = [
        "extension" => "",
        "number" => ""
    ];

    /**
     * Number client
     * @var string
     */
    public $to_number;

    /**
     * @var string
     */
    public $line_number;

    /**
     * @param $command_id
     * @return $this
     */
    public function command_id($command_id)
    {
        $this->command_id = $command_id;

        return $this;
    }

    /**
     * @param $to_number
     * @return $this
     */
    public function to_number($to_number)
    {
        $this->to_number = $to_number;

        return $this;
    }

    /**
     * @param $extension
     * @return $this
     */
    public function extension($extension)
    {
        $this->from['extension'] = $extension;

        return $this;
    }

    /**
     * @param $fromNumber
     * @return $this
     */
    public function fromNumber($fromNumber)
    {
        $this->from['number'] = $fromNumber;

        return $this;
    }

    /**
     * @param $line_number
     * @return $this
     */
    public function line_number($line_number)
    {
        $this->line_number = $line_number;

        return $this;
    }

}