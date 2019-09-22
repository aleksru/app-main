<?php


namespace App\Console\Commands;

use App\Models\City;
use App\Services\Metro\UpdateStations;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateMetroStations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metro:update-stations {--city=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update metro stations';

    /**
     * UpdateMetroStations constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        if($optionCity = $this->option('city')) {
            $city = City::where('name', 'like', $optionCity)->first();
            if(!$city){
                throw new \Exception("City not found in database!");
            }
            (new UpdateStations($city))->update();
        }else{
            foreach (config('metro.aliases') as $alias => $key){
                $city = City::where('name', 'like', $alias)->first();
                if($city){
                    (new UpdateStations($city))->update();
                }else{
                    Log::error('Ошибка обновления станций метро!!! Не найден город ' . $alias);
                }
            }
        }
    }
}