# OneID for Laravel Socialite

OneID (Uzbekistan SSO) provider for [SocialiteProviders](https://github.com/SocialiteProviders/Providers).

## Installation

```bash
composer require socialiteproviders/oneid
```

## Configuration

Add to `config/services.php`:

```php
'oneid' => [
    'client_id' => env('ONEID_CLIENT_ID'),
    'client_secret' => env('ONEID_CLIENT_SECRET'),
    'redirect' => env('ONEID_REDIRECT_URI'),
],
```

## Laravel 11+ Event Listener

```php
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;

Event::listen(function (SocialiteWasCalled $event) {
    $event->extendSocialite('oneid', \SocialiteProviders\OneID\Provider::class);
});
```

## Usage

```php
return Socialite::driver('oneid')->redirect();
```

## Endpoints

- Authorize / Token / Userinfo: `https://sso.egov.uz/sso/oauth/Authorization.do`

## Returned User fields

- id (user_id or pin)
- name (full_name or first_name + sur_name)
- email (if provided)

## License

MIT
