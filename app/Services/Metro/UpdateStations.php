<?php


namespace App\Services\Metro;


use App\Models\City;
use App\Services\Metro\Sources\MetroSourceInterface;

class UpdateStations
{
    /**
     * @var City
     */
    private $city;

    /**
     * @var null|string
     */
    private $service;

    /**
     * UpdateStations constructor.
     * @param City|null $city
     * @param null|string $service
     */
    public function __construct(City $city, ?string $service = null)
    {
        $this->city = $city;
        $this->service = $service;
    }

    /**
     *
     */
    public function update()
    {
        $service = $this->getService();
        $service->update();
    }

    /**
     * @return MetroSourceInterface
     * @throws \Exception
     */
    private function getService() : MetroSourceInterface
    {
        if($this->service !== null){
            if(!array_key_exists($this->service, config('metro.sources'))){
                throw new \Exception("Service {$this->service} not found in config!");
            }
            $service = config('metro.sources')[$this->service];

            return app($service, ['city' => $this->city]);
        }

        $defaultService = config('metro.default');

        return app(config('metro.sources')[$defaultService], ['city' => $this->city]);
    }
}