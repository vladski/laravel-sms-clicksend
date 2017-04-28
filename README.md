# ClickSend notifications channel for Laravel 5.4+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/smsc-ru.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/smsc-ru)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/smsc-ru/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/smsc-ru)
[![StyleCI](https://styleci.io/repos/65589451/shield)](https://styleci.io/repos/65589451)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/aceefe27-ba5a-49d7-9064-bc3abea0abeb.svg?style=flat-square)](https://insight.sensiolabs.com/projects/aceefe27-ba5a-49d7-9064-bc3abea0abeb)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/smsc-ru.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/smsc-ru)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/smsc-ru/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/smsc-ru/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/smsc-ru.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/smsc-ru)

This package makes it easy to send notifications using [clicksend.com](//clicksend.com) with Laravel 5.4+.
Uses CLickSend Libarary - PHP API wrapper [https://github.com/ClickSend/clicksend-php]

## Contents

- [Installation](#installation)
    - [Setting up the ClickSend service](#setting-up-the-ClickSend-service)
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
composer require laravel-notification-channels/smsc-ru
```

Then you must install the service provider:
```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\ClickSend\ClickSendServiceProvider::class,
],
```

### Setting up the ClickSend service

Add your ClickSend login, secret key (hashed password) and default sender name (or phone number) to your `config/services.php`:

```php
// config/services.php
...
'clicksend' => [
    'login'  => env('SMSCRU_LOGIN'),
    'secret' => env('SMSCRU_SECRET'),
    'sender' => 'John_Doe'
],
...
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\ClickSend\ClickSendMessage;
use NotificationChannels\ClickSend\ClickSendChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [ClickSendChannel::class];
    }

    public function toClickSend($notifiable)
    {
        return ClickSendMessage::create("Task #{$notifiable->id} is complete!");
    }
}
```

In your notifiable model, make sure to include a routeNotificationForSmsRu() method, which return the phone number.

```php
public function routeNotificationForSmsRu()
{
    return $this->phone;
}
```

### Available methods

`from()`: Sets the sender's name or phone number.

`content()`: Sets a content of the notification message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email jhaoda@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [JhaoDa](https://github.com/jhaoda)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
