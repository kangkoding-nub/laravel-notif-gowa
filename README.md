# WAHA notifications channel for Laravel 11+


[![Latest Version on Packagist](https://img.shields.io/packagist/v/shiroamada/waha-laravel-notification.svg?style=flat-square)](https://packagist.org/packages/shiroamada/waha-laravel-notification)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/shiroamada/waha-laravel-notification/master.svg?style=flat-square)](https://travis-ci.org/shiroamada/waha-laravel-notification)
[![StyleCI](https://styleci.io/repos/108503043/shield)](https://styleci.io/repos/108503043)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/waha-laravel-notification.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/waha-laravel-notification)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/waha-laravel-notification/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/shiroamada/waha-laravel-notification/?branch=main)
[![Total Downloads](https://img.shields.io/packagist/dt/shiroamada/waha-laravel-notification.svg?style=flat-square)](https://packagist.org/packages/shiroamada/waha-laravel-notification)

This package makes it easy to send notifications using [https://waha.devlike.pro/](https://waha.devlike.pro/) with Laravel 11+.

Code Reference from laravel-notification-channels/smsc-ru

## Contents

- [Installation](#installation)
    - [Setting up the WAHA service](#setting-up-the-waha-service)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

```bash
composer require kangkoding-nub/waha-laravel-notification
```

Then you must install the service provider:
```php
// bootstrap/providers.php
[
    ...
    NotificationChannels\Waha\WahaServiceProvider::class,
],
```

### Setting up the WAHA service

Add your waha instanceId and apiKey to your `config/services.php`:

```php
// config/services.php
...
'waha' => [
    'apiUrl' => env('WAHA_API_URL'),
    'sessionId' => env('WAHA_SESSION_ID'),
    'apiKey' => env('WAHA_API_KEY'),
    'isEnable' => env('WAHA_ENABLE') ?? 0,
    'isDebug' => env('WAHA_DEBUG_ENABLE') ?? 0,
    'debugReceiveNumber' => env('WAHA_DEBUG_RECEIVE_NUMBER'),
],
...
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Waha\GowaMessage;
use NotificationChannels\Waha\GowaChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [GowaChannel::class];
    }

    public function toWaha($notifiable)
    {
        return GowaMessage::create("Task #{$notifiable->id} is complete!");
    }
}
```

In your notifiable model, make sure to include a routeNotificationForWaha() method, which return the phone number.

```php
public function routeNotificationForWaha()
{
    return $this->mobile; //depend what is your db field
}
```

### Available methods

`content()`: Set a content of the notification message.

`sendAt()`: Set a time for scheduling the notification message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please use the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [kangKoding-Nub](https://github.com/kangkoding-nub)
- [All Contributors](../../contributors)

## License

The  Apache License Version 2.0. Please see [License File](LICENSE.md) for more information.
