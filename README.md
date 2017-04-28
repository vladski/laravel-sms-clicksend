# ClickSend notifications channel for Laravel 5.4+

This package makes it easy to send notifications using [clicksend.com](//clicksend.com) with Laravel 5.4+.
Uses ClickSend service provider libarary - PHP API wrapper [https://github.com/ClickSend/clicksend-php]

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

Install the package via composer:
```bash
composer require vladski/laravel-sms-clicksend
```

Install the service provider:
```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\ClickSend\ClickSendServiceProvider::class,
],
```

### Setting up the ClickSend service

Add your ClickSend username, api_key and optional default sender sms_from to your `config/services.php`:

```php
// config/services.php
...
'clicksend' => [
	'username' => env('CLICKSEND_USERNAME'),
	'api_key'  => env('CLICKSEND_API_KEY'),
	'sms_from' => env('CLICKSEND_SMS_FROM'),
],
...
```

## Usage

Use the channel in your `via()` method inside the notification - see example:

```php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\ClickSend\ClickSendMessage;
use NotificationChannels\ClickSend\ClickSendChannel;

class ClickSendTest extends Notification
{

    public $token;

    /**
     * Create a notification instance.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return [ClickSendChannel::class];
    }

    public function toClickSend($notifiable)
    {
        return ClickSendMessage::create("SMS test to user #{$notifiable->id} with token {$this->token} by ClickSend");
    }
}
```
In notifiable model e.g. User, include a routeNotificationForClickSend() method, which returns recipient (user) mobile number:

```php
public function routeNotificationForClickSend()
{
    return $this->phone;
}
```
### Available ClickSendMessage methods

`create($content)`: static method to initiate message with passed content

`from($from)`: sets the sender's mobile number (available from service provider)

`content($content)`: sets a content - any parameters used by notification to compose the message (along with notifiable model)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

Incomplete
``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [vladski](https://github.com/vladski)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
