<!DOCTYPE html>
<html>
<head>
    <title>Mail receive daily weather forecast information</title>
</head>
<body> 
    <h1>Weather Forecast for {{ $weatherData['location']['name'] }}</h1>
    @foreach($weatherData['forecast']['forecastday'] as $forecast)
        <div>
            <h2>Date: {{ $forecast['date'] }}</h2>
            <p>Temperature: {{ $forecast['day']['avgtemp_c'] }}°C</p>
            <p>Wind: {{ $forecast['day']['maxwind_mph'] }} M/S</p>
            <p>Humidity: {{ $forecast['day']['avghumidity'] }}%</p>
            <img src="https:{{ $forecast['day']['condition']['icon'] }}" alt="Weather icon"> 
            <p>{{ $forecast['day']['condition']['text'] }}</p>
        </div>
        <br>
    @endforeach
    
    <p>
        If you no longer wish to receive these emails, you can 
        <a href="{{ url('/api/unsubscribe/' . $verificationCode) }}">unsubscribe here</a>.
    </p>
</body>
</html>