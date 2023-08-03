# weather-api

## Introduction

`weather-api` takes location data (longitude and latitude) as parameter and returns the current weather data for the given location.

The features of the APi are:

- User can signup, login and logout
- Loggedin user can get the current weather data

## Setup

### Prerequisite

- PHP
- MySQL

### Install

- Install dependency

```
composer install
```

- Setup environment variable

```
cp .env.example .env
```

Update the environment variables with necessary parameters. For `OPEN_WEATHER_MAP_API_KEY` get a new API Key from <https://openweathermap.org/>.

Add database credentials to the `.env` file.

Generate `JWT_SECRET`

```
php artisan jwt:secret
```

- Starting the app

```
php artisan serve
```

## Implementation

### Endpoints

There are three endpoints available for user register, login and fetching the weather.
The `Postman Collection` is available from the link <https://api.postman.com/collections/6610039-a367e4da-d9ad-4e74-9ac8-a55f7d5d0109?access_key=PMAT-01H6XTBJ9S96MXJ9S0TW0KMDAQ> or from `weather-api.postman_collection.json` available in this repo.

The API is also hosted on <https://weather-api-laravel-799df554bbaa.herokuapp.com>.

### Status Codes

simpleblog API returns the following status codes.

| Status Code |         Description          |
| :---------- | :--------------------------: |
| 200         |             `OK`             |
| 401         | `BAD REQUEST` `Unauthorized` |
| 500         |   `Internal Server Error`    |

#### Success Response Example

```
{
    success: true
    message: 'Success',
    data: []
}
```

#### Failure Response Example

```
{
    success: false
    status: 401,
    message: 'Failed, Unfortunately.'
}
```

### Authentication

The weather endpoint `/api/weather?lat=51.504694&lon=-0.113222` can be authenticated by sending `Authorization` header with the JWT Token from `/api/login/` with `Bearer` prefix.

#### Auth header for API calls

```
{
    Authorization: Bearer JWT_token,
}
```

## API Endpoints

#### 1. Register.

`POST /api/register/`

##### Auth header not required.
##### Request Body
```
{
    "name": "john",
    "email": "john@test.com",
    "password": "testpassword"
}
```

##### Response Example

```
{
    "success": true,
    "message": "User created successfully",
    "data": {
        "name": "john",
        "email": "john@test.com",
        "updated_at": "2023-08-03T13:27:13.000000Z",
        "created_at": "2023-08-03T13:27:13.000000Z",
        "id": 5
    }
}
```

#### 2. Login.

`POST /api/login`

##### Auth header not required.
##### Request Body
```
{
    "email": "john@test.com",
    "password": "testpassword"
}
```
##### Response Example

```
{
    "success": true,
    "message": "Token created successfully",
    "data": "eyJ0eXAizI1NiJ9.eyJpc3MiOiJIn0.YVFo2d7Hw"
}
```

#### 3. Logout, invalidating the current token.

`GET /api/logout`

##### Auth header required.
```
{
    Authorization: Bearer JWT_token,
}
```
##### Response Example

```
{
    "success":true,
    "message":"User has been logged out"
}
```

#### 4. Get weather data.

`GET api/weather?lat={latitude}&lon={longitude}`

###### Auth header required.
```
{
    Authorization: Bearer JWT_token,
}
```

##### Response Example

```
{
    "success": true,
    "message": "Weather data",
    "data": {
        "coord": {
            "lon": -0.1138,
            "lat": 51.5036
        },
        "weather": [
            {
                "id": 804,
                "main": "Clouds",
                "description": "overcast clouds",
                "icon": "04d"
            }
        ],
        "base": "stations",
        "main": {
            "temp": 293.43,
            "feels_like": 293.05,
            "temp_min": 291.59,
            "temp_max": 294.89,
            "pressure": 1005,
            "humidity": 59
        },
        "visibility": 10000,
        "wind": {
            "speed": 3.6,
            "deg": 320
        },
        "clouds": {
            "all": 100
        },
        "dt": 1691068658,
        "sys": {
            "type": 2,
            "id": 2075535,
            "country": "GB",
            "sunrise": 1691036794,
            "sunset": 1691092002
        },
        "timezone": 3600,
        "id": 6545250,
        "name": "Lambeth",
        "cod": 200
    }
}
```

```
{
    "success": false,
    "message": "wrong longitude",
    "data": ""
}
```
