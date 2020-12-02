<?php


namespace App\Services\Operator\Calls\Commands;

use App\Services\Mango\Commands\Route;

class RouteToReclamation extends Route
{
    /**
     * RouteToReclamation constructor.
     * @param string $call_id
     * @param string|null $to_number
     * @param null $command_id
     * @param null $sip_headers
     * @param null $display_name
     */
    public function __construct(string $call_id, string $to_number = null, $command_id = null, $sip_headers = null, $display_name = null)
    {
        $to_number = $to_number ? $to_number : config('mango.numbers.reclamations_group');
        parent::__construct($call_id, $to_number, $command_id, $sip_headers, $display_name);
    }
}
