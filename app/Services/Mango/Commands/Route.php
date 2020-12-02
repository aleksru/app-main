<?php


namespace App\Services\Mango\Commands;


use Illuminate\Support\Str;

class Route implements \JsonSerializable
{
    protected $command_id;
    protected $call_id;
    protected $to_number;
    protected $sip_headers;
    protected $display_name;

    /**
     * Route constructor.
     * @param string $call_id
     * @param string $to_number
     * @param null $command_id
     * @param null $sip_headers
     * @param null $display_name
     */
    public function __construct(string $call_id, string $to_number, $command_id = null, $sip_headers = null, $display_name = null)
    {
        $this->command_id = $command_id ? $command_id :Str::uuid();
        $this->call_id = $call_id;
        $this->to_number = $to_number;
        $this->sip_headers = $sip_headers;
        $this->display_name = $display_name;
    }

    public function __toString()
    {
        return implode(', ', $this->jsonSerialize());
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
