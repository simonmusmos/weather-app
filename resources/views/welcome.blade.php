@extends('layouts.app')

@section('content')
    <div class="main-container">
        <div class="jumbotron bg-black mb-0 pb-3 pt-5" id="header">
            <h1>Weather</h1>      
            {{-- <p>The latest update on weather in Japan.</p> --}}
        </div>
        <div class="row sub-container">
            <div class="col-lg-6 col-12 d-lg-block" id="list-container">
                <div class="row">
                    <div class="col-md-12" id="search-container">
                        <div class="scrollable m-4">
                            <form action="/" method="GET" id="search-form">
                                <input type="text" class="form-control" id="search-bar" placeholder="Search a City">
                            </form>
                        </div>
                        
                    </div>
                    <div class="col-md-12">
                        <div class="scrollable" id="city-container">
                            @foreach ($places as $key => $place)
                                <div class="card m-4 weather-card" data-id="{{ $place['id'] }}" data-lat="{{ $place['lat'] }}" data-long="{{ $place['long'] }}" data-value="{{ $key }}" style="background-image: url('{{ $place['img_src'] }}');">
                                    <div class="card-body bg-gray p-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <h2 class="text-white">
                                                    {{ $place['name'] }} <span class="small">({{ $place['region'] }})</span>
                                                </h2>
                                                <h6 class="text-white">{{ $place['country'] }} &nbsp<img src='https://flagcdn.com/w20/{{ strtolower($place['countryCode']) }}.png'>
                                                </h6>
                                                <h6 class="text-white mb-4">
                                                    {{ $place['time'] }}
                                                </h6>
                                                <h6 class="text-white">
                                                    {{ $place['weather_description'] }}
                                                </h6>
                                            </div>
                                            <div class="col-6 text-right">
                                                <h1 class="display-5 mb-4">{{ $place['temperature'] }}</h1>   
                                                <h6 class="text-white">
                                                    H:{{ $place['temperature_max'] }} L:{{ $place['temperature_min'] }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12 d-none" id="detail-container">
                <div class="scrollable" id="detail-container-scrollable">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card m-4 detail-card" style="background-image: url('http://127.0.0.1:8000/assets/images/weather/03n.jpg');">
                                <div class="card-body pt-3 position-relative">
                                    <button class="btn btn-primary top-right-button" id="favorite-button">Add to list</button>
                                    <div class="row mt-4">
                                        <div class="col-md-12 text-center">
                                            <h1 id="detail-city-name">Tokyo</h1>   
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <h4 id="detail-country-name">Tokyo</h4>   
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <h1 class="display-4" id="detail-temperature">26°C</h1>   
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <h4 id="detail-weather-description">Partly Cloudy</h4>   
                                        </div>
                                        <div class="col-md-12 text-center mb-3">
                                            <h5 id="detail-high-low">H:26°C L:26°C</h5>   
                                        </div>
                                        <div class="col-md-12 text-center mb-3">
                                            <div class="card frosted-container">
                                                <div class="card-header">
                                                    <span>The forecast below shows weather changes every 3 hours.</span>
                                                </div>
                                                <div class="card-body py-1">
                                                    <div class="row" id="hourly-forecast-container">
                                                        {{-- <div class="col-md-2 px-3">
                                                            <div class="row text-center frosted-container py-3">
                                                                <div class="col-md-12">
                                                                    <span>
                                                                        <h5 style="display: inline">1</h5><h6 style="display: inline">AM</h6>
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <img src="{!! url('assets/images/weather/icons/01d.png') !!}" alt="" width="30">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h5 style="display: inline">26°C</h5>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <div class="card frosted-container">
                                                <div class="card-header">
                                                    <span>5-DAY FORECAST</span>
                                                </div>
                                                <div class="card-body py-1">
                                                    <div class="row" id="daily-forecast-container">
                                                        <div class="col-md-12 py-2 custom-border-bottom">
                                                            <div class="row">
                                                                <div class="col-md-3 text-middle">
                                                                    <h5 class="mb-0">September 11, 2024</h5>
                                                                    <span>Thursday</span>
                                                                </div>
                                                                <div class="col-md-2 text-center text-middle">
                                                                    <img src="{!! url('assets/images/weather/icons/01d.png') !!}" alt="" width="30">
                                                                    <h6>Partly Cloudy</h6>
                                                                </div>
                                                                <div class="col-md-2 text-center text-middle">
                                                                    <h5 class="text-middle">26°C</h5>
                                                                    <span>H:26°C L:26°C</span>
                                                                </div>
                                                                <div class="col-md-2 text-center text-middle">
                                                                    <h5 class="text-middle">26%</h5>
                                                                    <span>Humidity</span>
                                                                </div>
                                                                <div class="col-md-3 text-center text-middle">
                                                                    <h5 class="text-middle">26%</h5>
                                                                    <span>Chance of rain</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 py-2 custom-border-bottom">
                                                            <div class="row">
                                                                <div class="col-md-3 text-middle">
                                                                    <h5 class="mb-0">September 11, 2024</h5>
                                                                    <span>Thursday</span>
                                                                </div>
                                                                <div class="col-md-2 text-center text-middle">
                                                                    <img src="{!! url('assets/images/weather/icons/01d.png') !!}" alt="" width="30">
                                                                    <h6>Partly Cloudy</h6>
                                                                </div>
                                                                <div class="col-md-2 text-center text-middle">
                                                                    <h5 class="text-middle">26°C</h5>
                                                                    <span>H:26°C L:26°C</span>
                                                                </div>
                                                                <div class="col-md-2 text-center text-middle">
                                                                    <h5 class="text-middle">26%</h5>
                                                                    <span>Humidity</span>
                                                                </div>
                                                                <div class="col-md-3 text-center text-middle">
                                                                    <h5 class="text-middle">26%</h5>
                                                                    <span>Chance of rain</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 py-2 custom-border-bottom">
                                                            <div class="row">
                                                                <div class="col-md-3 text-middle">
                                                                    <h5 class="mb-0">September 11, 2024</h5>
                                                                    <span>Thursday</span>
                                                                </div>
                                                                <div class="col-md-2 text-center text-middle">
                                                                    <img src="{!! url('assets/images/weather/icons/01d.png') !!}" alt="" width="30">
                                                                    <h6>Partly Cloudy</h6>
                                                                </div>
                                                                <div class="col-md-2 text-center text-middle">
                                                                    <h5 class="text-middle">26°C</h5>
                                                                    <span>H:26°C L:26°C</span>
                                                                </div>
                                                                <div class="col-md-2 text-center text-middle">
                                                                    <h5 class="text-middle">26%</h5>
                                                                    <span>Humidity</span>
                                                                </div>
                                                                <div class="col-md-3 text-center text-middle">
                                                                    <h5 class="text-middle">26%</h5>
                                                                    <span>Chance of rain</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            $('#loader').show();
            var key = $('#search-bar').val();
            $('#city-container').html('');
            var city_html = '';
            $.ajax({
                url: "{{ route('weather.index') }}?q=" + key,  // The route you created
                type: 'GET',
                success: function(response) {
                    $('#loader').hide();  // Hide loader once data is loaded
                    $.each(response, function(key, val) {
                        console.log(val);
                        city_html += '<div class="card m-4 weather-card" data-id="' + val.id + '" data-lat="' + val.lat + '" data-long="' + val.long + '" data-value="' + val.name + '" style="background-image: url(\'' + val.img_src + '\');">'+
                                '    <div class="card-body bg-gray p-3">'+
                                '        <div class="row">'+
                                '            <div class="col-md-6">'+
                                '                <h2 class="text-white">'+
                                                        val.name + ' <span class="small">(' + val.region + ')</span>' +
                                '                </h2>'+
                                '                <h6 class="text-white">'+
                                                        val.country + " &nbsp<img src='https://flagcdn.com/w20/" + val.countryCode.toLowerCase() + ".png'>" +
                                '                </h6>'+
                                '                <h6 class="text-white mb-4">'+
                                                        val.time +
                                '                </h6>'+
                                '                <h6 class="text-white">'+
                                                        val.weather_description+
                                '                </h6>'+
                                '            </div>'+
                                '            <div class="col-md-6 text-right">'+
                                '                <h1 class="display-4 mb-4">' + val.temperature + '</h1>  '+ 
                                '                <h6 class="text-white">'+
                                '                    H:' + val.temperature_max + ' L:' + val.temperature_min + 
                                '                </h6>'+
                                '            </div>'+
                                '        </div>'+
                                '    </div>'+
                                '</div>';
                    });
                    $('#city-container').html(city_html);
                },
                error: function() {
                    $('#loader').hide();
                }
            });
        });
        $(document).on('click', '.weather-card', function () {
            $('#loader').show();
            $('#detail-container').addClass('d-none');
            $('#hourly-forecast-container').html('');
            $('#daily-forecast-container').html('');
            $('#favorite-button').attr('data-id', '');
            var hourly_forecast_html = "";
            var daily_forecast_html = "";
                $.ajax({
                url: "{{ route('weather.index') }}?city=" + $(this).data('value') + "&lat=" + $(this).data('lat') + "&long=" + $(this).data('long') + "&id=" + $(this).data('id'),  // The route you created
                type: 'GET',
                success: function(response) {
                    $('#loader').hide();  // Hide loader once data is loaded
                    $.each(response, function(key, val) {
                        console.log(val);
                        $('.detail-card').css('background-image', 'url("' + val.img_src + '")');
                        $('#detail-city-name').html(val.name + " <span class='small'>(" + val.region + ")</span>");
                        $('#detail-country-name').html(val.country + " &nbsp<img src='https://flagcdn.com/w40/" + val.countryCode.toLowerCase() + ".png'>");
                        $('#detail-temperature').html(val.temperature);
                        $('#detail-weather-description').html(val.weather_description);
                        $('#detail-high-low').html('H:' + val.temperature_max + ' L:' + val.temperature_min);
                        $('#detail-container').removeClass('d-none');
                        $('#favorite-button').attr('data-id', val.id);
                        if (val.is_favorite == true) {
                            $('#favorite-button').html('Remove from Favorites');
                        } else {
                            $('#favorite-button').html('Add to Favorites');
                        }
                        $.each(val.hourly_forecast, function(key1, val1) {
                            hourly_forecast_html += '<div class="col-md-2 px-3">'+
                            '    <div class="row text-center frosted-container py-3">'+
                            '        <div class="col-md-12">'+
                            '            <span>'+
                            '                <h5 style="display: inline">' + val1.time_hour + '</h5><h6 style="display: inline">' + val1.time_unit + '</h6>'+
                            '            </span>'+
                            '        </div>'+
                            '        <div class="col-md-12">'+
                            '            <img src="' + val1.icon + '" alt="" width="30">'+
                            '        </div>'+
                            '        <div class="col-md-12">'+
                            '            <h5 style="display: inline">' + val1.temperature + '</h5>'+
                            '        </div>'+
                            '    </div>'+
                            '</div>';
                        });
                        $('#hourly-forecast-container').html(hourly_forecast_html);

                        $.each(val.daily_forecast, function(key2, val2) {
                            console.log(val2);
                            daily_forecast_html += '<div class="col-md-12 py-2 custom-border-bottom">'+
                            '    <div class="row">'+
                            '        <div class="col-md-4 text-middle">'+
                            '            <h5 class="mb-0">' + val2.date + '</h5>'+
                            '            <span>' + val2.day_of_the_week + '</span>'+
                            '        </div>'+
                            '        <div class="col-md-2 text-center text-middle">'+
                            '            <img src="' + val2.icon + '" alt="" width="30">'+
                            '            <h6>' + val2.weather_description + '</h6>'+
                            '        </div>'+
                            '        <div class="col-md-2 text-center text-middle">'+
                            '            <h5 class="text-middle">' + val2.temperature + '</h5>'+
                            '            <span>H:' + val2.max_temp + ' L:' + val2.min_temp + '</span>'+
                            '        </div>'+
                            '        <div class="col-md-2 text-center text-middle">'+
                            '            <h5 class="text-middle">' + val2.humidity + '</h5>'+
                            '            <span>Humidity</span>'+
                            '        </div>'+
                            '        <div class="col-md-2 text-center text-middle">'+
                            '            <h5 class="text-middle">' + val2.pop + '</h5>'+
                            '            <span>Chance of rain</span>'+
                            '        </div>'+
                            '    </div>'+
                            '</div>';
                        });
                        $('#daily-forecast-container').html(daily_forecast_html);

                        $('#list-container').addClass('d-none');
                    });
                },
                error: function() {
                    $('#loader').hide();
                }
            });
        });

        $(document).on('click', '#favorite-button', function () {

            if ($(this).data('id') == '' || $(this).data('id') == undefined) {
                return;
            } else {
                $('#loader').show();
                $.ajax({
                    url: "{{ route('weather.favorite') }}?city_id=" + $(this).data('id'),  // The route you created
                    type: 'GET',
                    success: function(response) {
                        $('#loader').hide(); 
                        if (response.action == 'add') {
                            $('#favorite-button').html('Remove from Favorites');
                        } else {
                            $('#favorite-button').html('Add to Favorites');
                        }
                    },
                    error: function() {
                        $('#loader').hide();
                    }
                });
            }
        });
    </script>
    <script>
        function adjustHeight() {
            var headerHeight = document.querySelector('#header').offsetHeight;
            var searchDivHeight = document.querySelector('#search-container').offsetHeight;

            var offsetHeight = headerHeight + searchDivHeight;
            
            $('#city-container').css('max-height', `calc(100vh - ${offsetHeight}px)`);
            $('#detail-container-scrollable').css('max-height', `calc(100vh - ${headerHeight}px)`);
        }
    
        // Adjust height on load and resize
        window.addEventListener('load', adjustHeight);
        window.addEventListener('resize', adjustHeight);
    </script>
@endsection