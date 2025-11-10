<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stripe Keys
    |--------------------------------------------------------------------------
    |
    | The Stripe publishable and secret keys from your Stripe dashboard.
    | These are used to interact with Stripe's API.
    |
    */

    'key' => env('STRIPE_KEY'),

    'secret' => env('STRIPE_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Cashier Path
    |--------------------------------------------------------------------------
    |
    | This is the base URI path where Cashier's views, such as the payment
    | verification screen, will be available from. You're free to tweak
    | this path according to your preferences and application design.
    |
    */

    'path' => env('CASHIER_PATH', 'stripe'),

    /*
    |--------------------------------------------------------------------------
    | Stripe Webhooks
    |--------------------------------------------------------------------------
    |
    | Your Stripe webhook secret is used to verify that webhooks actually
    | came from Stripe. You'll find your webhook secret in your Stripe
    | dashboard for the configured webhook endpoint for your application.
    |
    */

    'webhook' => [
        'secret' => env('STRIPE_WEBHOOK_SECRET'),
        'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | This is the default currency that will be used when generating charges
    | from your application. Of course, you are welcome to use any of the
    | various world currencies that are currently supported via Stripe.
    |
    */

    'currency' => env('CASHIER_CURRENCY', 'usd'),

    /*
    |--------------------------------------------------------------------------
    | Currency Locale
    |--------------------------------------------------------------------------
    |
    | This is the default locale in which your money values are formatted in
    | for display. To utilize other locales besides the default en locale
    | verify you have the "intl" PHP extension installed on the system.
    |
    */

    'currency_locale' => env('CASHIER_CURRENCY_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Invoice Paper
    |--------------------------------------------------------------------------
    |
    | This option is the default paper size for all invoices generated using
    | Cashier. You are free to customize this settings based upon the usual
    | paper size used by the customers using your Laravel applications.
    |
    */

    'paper' => env('CASHIER_PAPER', 'letter'),

    /*
    |--------------------------------------------------------------------------
    | Stripe Logger
    |--------------------------------------------------------------------------
    |
    | This setting allows you to configure which logging channel will be used
    | by the Stripe library to write log messages. You are free to specify
    | any of your logging channels listed inside the "logging" config file.
    |
    */

    'logger' => env('CASHIER_LOGGER'),

    /*
    |--------------------------------------------------------------------------
    | Load Migrations
    |--------------------------------------------------------------------------
    |
    | This setting allows you to disable Cashier's automatic migration loading.
    | When disabled, you'll need to publish and run the migrations manually.
    |
    */

    'load_migrations' => false,

];
