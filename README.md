# Edmunds SDK
[![Version](https://img.shields.io/packagist/v/coderstephen/edmunds-sdk.svg)](https://packagist.org/packages/coderstephen/edmunds-sdk)
[![License](https://img.shields.io/packagist/l/coderstephen/edmunds-sdk.svg)](https://packagist.org/packages/coderstephen/edmunds-sdk)
[![Downloads](https://img.shields.io/packagist/dt/coderstephen/edmunds-sdk.svg)](https://packagist.org/packages/coderstephen/edmunds-sdk)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/coderstephen/edmunds-sdk.svg)](https://scrutinizer-ci.com/g/coderstephen/edmunds-sdk)

A simple but native implementation of a PHP SDK for the [Edmunds API](http://developer.edmunds.com). Provides local object representations of remote data.

## Installation
Install with [Composer](http://getcomposer.org), obviously:

```sh
$ composer require coderstephen/edmunds-sdk
```

## Usage
First, create an `ApiClient` instance, which connects to the Edmunds server:

```php
$client = new Edmunds\SDK\ApiClient('YOUR_API_KEY_HERE');
```

Pass in your Edmunds API key as the first argument. If you do not already have an API key, you will need to [register with Edmunds](http://edmunds.mashery.com/member/register/) first.

You can now access almost all of the Edmunds API with the client object. Currently, only the [Vehicle API]() has object wrappers. To make a raw request to any endpoint, use the `ApiClient::makeCall()` method:

```php
$response = $client->makeCall('/api/inventory/v2/inventories', [
    'zipcode' => '90404'
]);

foreach ($response->inventories as $inventory) {
    echo $inventory->vin;
}
```

See the [Edmunds API documentation](http://developer.edmunds.com/api-documentation/overview/index.html) for details on what endpoints are available and their usage.

### Vehicle API
A vehicle API client is provided to access Edmunds' extensive vehicle knowledge database. To use it, first create a `VehicleApiClient` object:

```php
$client = new Edmunds\SDK\VehicleApiClient('YOUR_API_KEY_HERE');
```

The `VehicleApiClient` class extends `ApiClient` and provides several easy-to-use wrapper methods for accessing data objects. Below is a short example of some of the things you can do with the SDK:

```php
use Edmunds\SDK;

$client = new SDK\VehicleApiClient('YOUR_API_KEY_HERE');

$makesIn2009 = $client->getMakes(null, 2009);
foreach ($makesIn2009->getModels() as $model) {
    $modelYear = $model->getYear(2009);
    printf('Model name: %s', $model->name);

    foreach ($modelYear->getStyles() as $style) {
        $firstPhoto = $style->getPhotos()[0];
        printf('Image URL: %s', $firstPhoto->getBestQualityUrl());
    }
}
```

### Caching
The application that this library was originally created for required making thousands of API calls while processing artificial intelligence instructions. To lighten the heavy load, this library provides a really simple API call caching solution using the filesystem. To use the caching mechanism, pass an `ApiCache` object when creating an API client:

```php
$cache = new Edmunds\SDK\ApiCache('/path/to/cache');
$client = new Edmunds\SDK\VehicleApiClient('YOUR_API_KEY_HERE', $cache);
```

Every time specific data about an entity is requested, the cache is checked first. If no cached data is found, an API call is made as normal, and the result is written to the cache for later.

## License
Licensed under the [Apache Public License 2.0](http://www.apache.org/licenses/LICENSE-2.0.html). See [the license file](LICENSE) for details.
