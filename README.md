# ClickSend notifications channel for Laravel 5.4+

This package makes it easy to send notifications using [clicksend.com](//clicksend.com) with Laravel 5.4+.
Uses ClickSend service provider libarary - PHP API wrapper [https://github.com/ClickSend/clicksend-php]

## Contents

- [Installation](#installation)
- [Usage](#usage)
- [Events](#events)
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

Add the service provider to `config/app.php`:
```php
...
'providers' => [
    ...
    NotificationChannels\ClickSend\ClickSendServiceProvider::class,
],
...
```

Add your ClickSend username, api_key and optional default sender sms_from to your `config/services.php`:

```php
...
'clicksend' => [
	'username' => env('CLICKSEND_USERNAME'),
	'api_key'  => env('CLICKSEND_API_KEY'),
	'sms_from' => env('CLICKSEND_SMS_FROM'), // optional
],
...
```

## Usage

Use ClickSendChannel in `via()` method inside your notification classes. Example:

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
        // statically create message object:
        
        $message = ClickSendMessage::create("SMS test to user #{$notifiable->id} with token {$this->token} by ClickSend");
        
        // OR instantiate:
        
        $message = new ClickSendMessage("SMS test to user #{$notifiable->id} with token {$this->token} by ClickSend");
        
       	// available methods:
       	
       	$message->content("SMS test to user #{$notifiable->id} with token {$this->token} by ClickSend");
       	$message->from('+6112345678'); // override sms_from from config
       	
       	return $message;
    }
}
```

In notifiable model (User), include method `routeNotificationForClickSend()` that returns recipient mobile number:

```php
...
public function routeNotificationForClickSend()
{
    return $this->phone;
}
...
```
From controller then send notification standard way:
```php
	$user = User::find(1);
	
	try {
		$user->notify(new ClickSendTest('ABC123'));
	}
	catch (\Exception $e) {
		// do something when error
		return $e->getMessage();
	}
```

## Events
Following events are triggered by Notification. By default:
- Illuminate\Notifications\Events\NotificationSending
- Illuminate\Notifications\Events\NotificationSent

and this channel triggers one when submission fails for any reason:
- Illuminate\Notifications\Events\NotificationFailed

To listen to those events create listener classes in `app/Listeners` folder e.g. to log failed SMS:

```php
namespace App\Listeners;
	
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use NotificationChannels\ClickSend\ClickSendChannel;
	
class NotificationFailedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Notification failed event handler
     *
     * @param  NotificationFailed  $event
     * @return void
     */
    public function handle(NotificationFailed $event)
    {
        // Handle fail event for ClickSend
        //
        if($event->channel == ClickSendChannel::class) {
	
            echo 'failed'; dump($event);
            
            $logData = [
            	'notifiable'    => $event->notifiable->id,
            	'notification'  => get_class($event->notification),
            	'channel'       => $event->channel,
            	'data'      => $event->data
            	];
            	
            Log::error('Notification Failed', $logData);
         }
         // ... handle other channels ...
    }
}
```
 
 
 
Then register listeners in `app/Providers/EventServiceProvider.php`
```php
...
protected $listen = [

	'Illuminate\Notifications\Events\NotificationFailed' => [
		'App\Listeners\NotificationFailedListener',
	],

	'Illuminate\Notifications\Events\NotificationSent' => [
		'App\Listeners\NotificationSentListener',
	],

	'Illuminate\Notifications\Events\NotificationSending' => [
		'App\Listeners\NotificationSendingListener',
	],
];
...
```


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
