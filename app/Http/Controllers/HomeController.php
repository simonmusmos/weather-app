<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\City;

class HomeController extends Controller
{
    protected $api_key;

    protected $units;

    protected $places;

    protected $rapid_api_key;

    public function __construct() {
        $this->api_key = env('OPENWEATHER_API_KEY');
        $this->units = env('OPENWEATHER_UNITS', 'metric');
        $this->rapid_api_key = env('RAPIDAPI_API_KEY');
        $this->places = [
            'Tokyo' => [
                'lat' => '35.6764',
                'long' => '139.6500',
                'country' => 'Japan',
                'countryCode' => 'JP',
            ],
            'Yokohama' => [
                'lat' => '35.4437',
                'long' => '139.6380',
                'country' => 'Japan',
                'countryCode' => 'JP',
            ],
            'Kyoto' => [
                'lat' => '35.0116',
                'long' => '135.7681',
                'country' => 'Japan',
                'countryCode' => 'JP',
            ],
            'Osaka' => [
                'lat' => '34.6937',
                'long' => '135.5023',
                'country' => 'Japan',
                'countryCode' => 'JP',
            ],
            'Sapporo' => [
                'lat' => '43.0618',
                'long' => '141.3545',
                'country' => 'Japan',
                'countryCode' => 'JP',
            ],
            'Nagoya' => [
                'lat' => '35.1815',
                'long' => '136.9066',
                'country' => 'Japan',
                'countryCode' => 'JP',
            ],
        ];
    }

    public function index(Request $request) {
        $current_time = time();
        $places = [];
        $cities = [];
        if (($request->city ?? '') != '') {
            $search_result = $this->getReverseSearchResults($request->id)['data'];

            $places[] = [
                'id' => $search_result['id'],
                'name' => ucwords($search_result['name']),
                'region' => ucwords($search_result['region']),
                'lat' => $search_result['latitude'],
                'long' => $search_result['longitude'],
                'country' => $search_result['country'],
                'countryCode' => $search_result['countryCode'],
            ];
        } else if (($request->q ?? '') != '') {
            $search_results = $this->getSearchResults($request->q);
            // dd($search_results);
            foreach (($search_results['data'] ?? []) as $result) {
                // dd($result);
                $places[] = [
                    'id' => $result['id'],
                    'name' => ucwords($result['name']),
                    'region' => ucwords($result['region']),
                    'lat' => $result['latitude'],
                    'long' => $result['longitude'],
                    'country' => $result['country'],
                    'countryCode' => $result['countryCode'],
                ];
            }
        } else {
            $ids = City::pluck('city_id');
            foreach ($ids as $id) {
                $search_result = $this->getReverseSearchResults($id)['data'];
                $places[] = [
                    'id' => $search_result['id'],
                    'name' => ucwords($search_result['name']),
                    'region' => ucwords($search_result['region']),
                    'lat' => $search_result['latitude'],
                    'long' => $search_result['longitude'],
                    'country' => $search_result['country'],
                    'countryCode' => $search_result['countryCode'],
                ];

                sleep(1.1);
            }
        }
        foreach ($places as $key => &$place) {
            $result = $this->getCurrentWeatherLatLong($place);
            if ($result['cod'] == 200) {
                $place['temperature'] = ($result['main']['temp'] ?? '') . '°C';
                $place['temperature_min'] = ($result['main']['temp_min'] ?? '') . '°C';
                $place['temperature_max'] = ($result['main']['temp_max'] ?? '') . '°C';
                // dd($result['timezone']);
                $place['time'] = date('h:i A', ($current_time + $result['timezone']));
                $place['weather_description'] = ucwords($result['weather'][0]['description']);
                $place['icon'] = $result['weather'][0]['icon'];
                $place['img_src'] = asset("assets/images/weather/{$result['weather'][0]['icon']}.jpg");
                if (($request->city ?? '') != '') {
                    $exist = City::where('city_id', $place['id'])->first();
                    if (!$exist) {
                        $place['is_favorite'] = false;
                    } else {
                        $place['is_favorite'] = true;
                    }
                    $forecast = $this->getWeatherForecastLatLong($place);
                    foreach ($forecast['list'] as $key2 => $detail) {
                        if ($key2 < 6) {
                            $place['hourly_forecast'][$key2]['time_hour'] = date('g', ($detail['dt'] + $result['timezone']));
                            $place['hourly_forecast'][$key2]['time_unit'] = date('A', ($detail['dt'] + $result['timezone']));
                            $place['hourly_forecast'][$key2]['time_full'] = date('M d, Y h:i A', ($detail['dt'] + $result['timezone']));
                            $place['hourly_forecast'][$key2]['icon'] = asset("assets/images/weather/icons/{$detail['weather'][0]['icon']}.png");
                            $place['hourly_forecast'][$key2]['temperature'] = $detail['main']['temp'] . '°C';
                        }
                    }
    
                    $place['daily_forecast'] = $this->summarizeForecast($forecast);
                }
                $cities[$key] = $place;
            } else {
                unset($place);
            }
        }
        
        if (($request->city ?? '') == '' && ($request->q ?? '') == '') {
            return view('welcome', [
                'places' => $cities
            ]);
        }

        return response()->json($cities, 200);
    }

    public function getCurrentWeather($city) {
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->api_key}&units={$this->units}");

        $responseBody = $response->body();
        
        $responseData = $response->json();
        return $responseData;
    }

    public function getCurrentWeatherLatLong($coords) {
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather?lat={$coords['lat']}&lon={$coords['long']}&appid={$this->api_key}&units={$this->units}");

        $responseBody = $response->body();
        
        $responseData = $response->json();
        return $responseData;
    }

    public function getWeatherForecast($city, $cnt = 0) {
        $response = Http::get("https://api.openweathermap.org/data/2.5/forecast?q={$city}&appid={$this->api_key}&units={$this->units}&cnt={$cnt}");

        $responseBody = $response->body();
        
        $responseData = $response->json();
        return $responseData;
    }

    public function getWeatherForecastLatLong($coords, $cnt = 0) {
        $response = Http::get("https://api.openweathermap.org/data/2.5/forecast?lat={$coords['lat']}&lon={$coords['long']}&appid={$this->api_key}&units={$this->units}&cnt={$cnt}");

        $responseBody = $response->body();
        
        $responseData = $response->json();
        return $responseData;
    }

    public function getSearchResults($search) {
        $response = Http::withHeaders([
                        'X-RapidAPI-Key' => $this->rapid_api_key,
                    ])
                    ->get("https://wft-geo-db.p.rapidapi.com/v1/geo/cities?namePrefix={$search}&limit=5&type=CITY");

        $responseBody = $response->body();
        
        $responseData = $response->json();
        return $responseData;
    }

    public function getReverseSearchResults($id) {
        $response = Http::withHeaders([
                        'X-RapidAPI-Key' => $this->rapid_api_key,
                    ])
                    ->get("https://wft-geo-db.p.rapidapi.com/v1/geo/cities/{$id}");

        $responseBody = $response->body();
        
        $responseData = $response->json();
        return $responseData;
    }

    function summarizeForecast($forecastList) {
        $dailyForecasts = [];
    
        foreach ($forecastList['list'] as $item) {
            $date = explode(' ', $item['dt_txt'])[0];  // Extract date (YYYY-MM-DD)
            $temp = $item['main']['temp'];
            $min_temp = $item['main']['temp_min'];
            $max_temp = $item['main']['temp_max'];
            $humidity = $item['main']['humidity'];
            $weather = $item['weather'][0]['main'];
            $icon = $item['weather'][0]['icon'];
            $description = $item['weather'][0]['description'];
            $pop = $item['pop'];
    
            if (!isset($dailyForecasts[$date])) {
                $dailyForecasts[$date] = [
                    'temps' => [],
                    'humidity' => [],
                    'weather' => [],
                    'icon' => [],
                    'description' => [],
                    'pop' => [],
                    'min_temp' => [],
                    'max_temp' => []
                ];
            }
    
            // Collect temperature and weather data
            $dailyForecasts[$date]['temps'][] = $temp;
            $dailyForecasts[$date]['humidity'][] = $humidity;
            $dailyForecasts[$date]['weather'][] = $weather;
            $dailyForecasts[$date]['icon'][] = $icon;
            $dailyForecasts[$date]['description'][] = $description;
            $dailyForecasts[$date]['pop'][] = $pop;
            $dailyForecasts[$date]['min_temp'][] = $min_temp;
            $dailyForecasts[$date]['max_temp'][] = $max_temp;
        }
    
        // Now calculate summary (average temp, most common weather condition)
        $summarizedData = [];
        $cnt = 0;
        foreach ($dailyForecasts as $date => $data) {
            if ($cnt < 5) {
                // dd($data);
                $avgTemp = array_sum($data['temps']) / count($data['temps']);
                $avgMinTemp = array_sum($data['min_temp']) / count($data['min_temp']);
                $avgMaxTemp = array_sum($data['max_temp']) / count($data['max_temp']);
                $avgHumidity = array_sum($data['humidity']) / count($data['humidity']);
                $commonWeather = $this->mostCommonValue($data['weather']);
                $commonIcon = substr($this->mostCommonValue($data['icon']), 0, -1) . 'd';
                $commonDescription = $this->mostCommonValue($data['description']);
                $avgPop = array_sum($data['pop']) / count($data['pop']);

                $summarizedData[] = [
                    'date' => date('F d, Y', strtotime($date)),
                    'day_of_the_week' => date('l', strtotime($date)),
                    'temperature' => round($avgTemp, 0) . '°C',
                    'humidity' => round($avgHumidity, 0) . '%',
                    'pop' => (round($avgPop, 0) * 100) . '%',
                    'min_temp' => round($avgMinTemp, 0) . '°C',
                    'max_temp' => round($avgMaxTemp, 0) . '°C',
                    'weather' => $commonWeather,
                    'icon' =>  asset("assets/images/weather/icons/{$commonIcon}.png"),
                    'weather_description' => ucwords($commonDescription)
                ];
            }
            
            $cnt++;
        }
    
        return $summarizedData;
    }

    function mostCommonValue($list) {
        $count = array_count_values($list);
        arsort($count);
        return key($count);  // Return most common weather condition
    }

    public function saveToFavorites(Request $request) {
        $exist = City::where('city_id', $request->city_id)->first();
        $action = "";
        if (!$exist) {
            City::create([
                'city_id' => $request->city_id
            ]);
            $action = "add";
        } else {
            $exist->delete();
            $action = "delete";
        }

        return response()->json(['action' => $action], 200);
    }
}
