# Laravel Facade para MercadoPago v0.6.3

* [Instalation](#install)
* [Configuration](#configuration)
* [Usage](#usage)
* [Troubleshooting](#troubleshooting)

<a name="install"></a>
### Installation

`composer require diego-mascarenhas/laravel-mercadopago-sdk`

Within config/app.php, add the following Provider and Alias

Provider

```php
'providers' => [
  // Others Providers...
  DiegoMascarenhas\LaravelMercadoPago\Providers\MercadoPagoServiceProvider::class,
  /*
   * Application Service Providers...
   */
],
```

Alias

```php
'aliases' => [
  // Others Aliases
  'MP' => DiegoMascarenhas\LaravelMercadoPago\Facades\MP::class,
],
```

<a name="configuration"></a>
### Configuration

Before configuring the APP ID and APP SECRET, run the following command:

`php artisan vendor:publish`

After executing the command, go to the `.env` file and add the `MP_APP_ID` and `MP_APP_SECRET` fields with the corresponding values of the `CLIENT_ID` and `CLIENT_SECRET` from your MercadoPago application.

To find out your `CLIENT_ID` and `CLIENT_SECRET` information, you can go here:

* [Credentials](https://www.mercadopago.com/mla/account/credentials?type=basic)

If you do not want to use the `.env` file, go to `config/mercadopago.php` and add your corresponding application details.


```php
return [
	'app_id' => env('MP_APP_ID', 'YOUR CLIENT ID'),
	'app_secret' => env('MP_APP_SECRET', 'YOUR CLIENT SECRET'),
  'app_ssl' => env('MP_APP_SSL', true),
	'app_sandbox' => env('MP_APP_DEBUG', false)
];
```

<a name="usage"></a>
### Usage

In this example, we will create a payment preference using the `MP` Facade.

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use MP;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MercadoPagoController extends Controller
{
  public function getCreatePreference()
  {
    $preferenceData = [
        'items' => [
            [
                'id' => 101,
                'category_id' => 'electronics',
                'title' => 'iPhone 14 Pro Max',
                'description' => 'iPhone 14 Pro Max 128GB - Black',
                'picture_url' => 'https://example.com/images/products/iphone-14-pro-max-black.png',
                'quantity' => 1,
                'currency_id' => 'ARS',
                'unit_price' => 150000
            ]
        ],
        'payer' => [
            'email' => 'customer@example.com'
        ],
        'payment_methods' => [
            'excluded_payment_types' => [
                ['id' => 'atm']
            ],
            'installments' => 12
        ],
        'back_urls' => [
            'success' => 'https://yourdomain.com/success',
            'failure' => 'https://yourdomain.com/failure',
            'pending' => 'https://yourdomain.com/pending'
        ],
        'auto_return' => 'approved',
        'notification_url' => 'https://yourdomain.com/notifications'
    ];

  	$preference = MP::create_preference($preferenceData);

  	return dd($preference);

  }
```

In this example, we will create a subscription (automatic debit) using the `MP` Facade.

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use MP;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MercadoPagoController extends Controller
{
  public function getCreatePreapproval()
  {
    $preapproval_data = [
      'payer_email' => 'customer@example.com',
      'back_url' => 'https://example.com/preapproval',
      'reason' => 'Premium Suscription',
      'external_reference' => $subscription->id,
      'auto_recurring' => [
        'frequency' => 1,
        'frequency_type' => 'months',
        'transaction_amount' => 99,
        'currency_id' => 'ARS',
        'start_date' => Carbon::now()->addHour()->format('Y-m-d\TH:i:s.BP'),
        'end_date' => Carbon::now()->addMonth()->format('Y-m-d\TH:i:s.BP'),
      ],
    ];

    MP::create_preapproval_payment($preapproval_data);

    return dd($preapproval);
  }
```

In the example, the use of the `Carbon` library can be seen to specify the start and end dates of the subscription, with a monthly frequency.

To the current date, an hour is added via `Carbon`, as otherwise MercadoPago might consider the date as past.


<a name="troubleshooting"></a>
### Troubleshooting

SSL Certificate Problem: Unable to Get Local Issuer Certificate

If you encounter an error stating SSL certificate problem: unable to get local issuer certificate, this typically occurs when the root certificates on your system are out of date or missing.

To resolve this issue, download the Latest CA Certificates Bundle from [CA Extract](https://curl.se/docs/caextract.html).