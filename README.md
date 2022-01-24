# File Notifier

![Packagist Version](https://img.shields.io/packagist/v/netzindianer/laravel-file-notifier?label=Version&style=for-the-badge)

## Installation

```shell
composer require netzindianer/laravel-file-notifier
php artisan vendor:publish --provider="Netzindianer\FileNotifier\FileNotifierProvider"
```

## Usage

### Email

This module will use Laravel Mailable to send raw logs in mail content to specified addresses.

#### Command

```shell
php artisan file-notifier:email \
  --file-name=/var/www/html/storage/logs/laravel.log \
  --seconds=3600 \
  --lines=300 \
  --email=example@email.com \
  --email=another@email.com \
  --subject="My Laravel App - laravel.log"
```

Or you can call `file-notifier:email:default` to read parameters from `config/file-notifier.php`

```shell
php artisan file-notifier:email:default
```

#### CRON

```shell
0 * * * * php artisan file-notifier:email:default >> /var/log/my_laravel_app_file_notifier.error.log
```

#### Laravel Schedule

[Documentation](https://laravel.com/docs/8.x/scheduling)

```php
// Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule
        ->command('file-notifier:email:default')
        ->hourly()
        ->appendOutputTo(storage_path('logs/file-notifier.log'));
}
```

### Discord

This module will send logs as attachment to given webhook
Documentation: [Here](https://discord.com/developers/docs/resources/webhook#execute-webhook)

#### Command

```shell
php artisan file-notifier:discord \
  --file-name=/var/www/html/storage/logs/laravel.log \
  --seconds=3600 \
  --lines=300 \
  --webhook-id=000000000000000000
  --webhook-token=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
  --message="{\"username\":\"My Laravel App\",\"content\":\"laravel.log\"}"
```

Or you can call `file-notifier:discord:default` to read parameters from `config/file-notifier.php`

```shell
php artisan file-notifier:discord:default
```

#### CRON

```shell
0 * * * * php artisan file-notifier:discord:default >> /var/log/my_laravel_app_file_notifier.error.log
```

#### Laravel Schedule

[Documentation](https://laravel.com/docs/8.x/scheduling)

```php
// Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule
        ->command('file-notifier:discord:default')
        ->hourly()
        ->appendOutputTo(storage_path('logs/file-notifier.log'));
}
```

### Own implementation

As example here is class, which will send logs in POST field to specified url
File Notifier is using [xtompie/result](https://packagist.org/packages/xtompie/result) package to handle result

```php
use Netzindianer\FileNotifier\FileNotifier;
use Xtompie\Result\Result;

class HttpPostNotifier 
{
    public function __construct(
        protected FileNotifier $fileNotifier, // This class handles checking if there is any new content in file
        protected HttpPostNotifierSender $sender, // This is our callable to handle sending logs
    ) {}
    
    public function __invoke(string $url): Result
    {
        // Call FileNotifier with appropriate arguments 
        $success = ($this->fileNotifier)(
            fileName: storage_path('logs/laravel.log'),
            seconds: 3600,
            lines: 300,
            sender: $this->sender->url($url),
        );
        return $success;
    }
}
```

```php
use Illuminate\Http\Client\Factory as HttpFactory;

class HttpPostNotifierSender 
{
    protected string $url;

    public function __construct(
        protected HttpFactory $http,
    ) {}
    
    public function url(string $url): static
    {
        $this->url = $url;
        return $this;
    }
    
    /**
    * @param string $content Last lines of specified file
    * @param string $fileName Name of file which was checked
    * @return bool FileNotifier will return result of this callable with Xtompie\Result\Result
     */
    public function __invoke(string $content, string $fileName): bool
    {
        $response = $this->http->post($this->url, [
            'fileName' => $fileName,
            'lastLinesOfFile' => $content,
        ]);
        return $response->successful();
    }
}
```

### Emails from database

If you want, for example, call `file-notifier:email` but read addresses from database, api works exactly as above,
so for emails you could do this:

```php
use \Netzindianer\FileNotifier\EmailNotifier\EmailNotifier;
use \App\Models\User;
use Xtompie\Result\Result;

class SendNewLogsToDevelopersUtil
{
    public function __construct(
        protected EmailNotifier $emailNotifier,
    ) {}

    public function __invoke(): Result
    {
        $users = User::where('is_developer', true)->get();
        $emails = $users->map(fn(User $user) => $user->email);

        return ($this->emailNotifier)(
            fileName: storage_path('logs/laravel.log'),
            seconds: 3600,
            lines: 300,
            emails: $emails,
            subject: "New logs for developers",
        );
    }
}
```

All notifiers in this package returns [xtompie results](https://packagist.org/packages/xtompie/result) without value or with error
