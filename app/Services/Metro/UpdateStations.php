<?php


namespace App\Services\Metro;


use App\Models\City;
use App\Models\Metro;
use App\Services\Metro\Sources\MetroSourceInterface;
use Illuminate\Support\Facades\Log;

class UpdateStations
{
    const LOG_TAG = 'metro';

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
        Log::channel(self::LOG_TAG)->error('Начало обновления ' . $this->city->name);
        try{
            $service = $this->getService();
            $data = $service->getLineStations();
            while ($lineStations = $data->popLineStation()){
                $isIgnoreLine = $this->isIgnoreLine($lineStations);
                if( ! $isIgnoreLine ){
                    $this->createStations($lineStations);
                }elseif ($isIgnoreLine && config('metro.is_delete_ignores')){
                    $this->deleteStations($lineStations);
                }
            }
        }catch (\Exception $e){
            Log::channel(self::LOG_TAG)->error($e->getMessage());
        }

    }

    private function createStations(LineStations $lineStations)
    {
        while ($station = $lineStations->popStation()){
            Log::channel(self::LOG_TAG)->error('Добавлена станция ' . $lineStations->getName() . '/' . $station->getName());
            Metro::firstOrCreate([
                'name' => $station->getName(),
                'city_id' => $this->city->id,
                'line' => $lineStations->getName()
            ]);
        }
    }

    private function deleteStations(LineStations $lineStations)
    {
        while ($station = $lineStations->popStation()){
            Log::channel(self::LOG_TAG)->error('Удалена станция ' . $lineStations->getName() . '/' . $station->getName());
            Metro::where('name', $station->getName())->where('line', $lineStations->getName())->delete();
        }
    }

    private function isIgnoreLine(Line $line) : bool
    {
        foreach (config('metro.ignore_lines_regex') as $item){
            if (preg_match($item, $line->getName())){
                return true;
            }
        }

        return false;
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