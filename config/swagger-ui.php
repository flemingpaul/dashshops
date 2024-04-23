<?php

use NextApps\SwaggerUi\Http\Middleware\EnsureUserIsAuthorized;

return [
    'files' => [
        [
            /*
             * The path where the swagger file is served.
             */
            'path' => 'api/documentation',

            /*
             * The versions of the swagger file. The key is the version name and the value is the path to the file.
             */
            'versions' => [
                'Authentication' => resource_path('swagger/authentication.json'),
                'Users' => resource_path('swagger/users.json'),
                'Retailers' => resource_path('swagger/retailers.json'),
                'Products' => resource_path('swagger/products.json'),
                'Notifications' => resource_path('swagger/notifications.json'),
                'Ratings' => resource_path('swagger/ratings.json'),
                'Categories' => resource_path('swagger/categories.json'),
                'Coupon' => resource_path('swagger/coupon.json'),
                'Favorites' => resource_path('swagger/favorites.json'),
                'States' => resource_path('swagger/states.json'),
                'Vips' => resource_path('swagger/vips.json'),
                'AppSettings' => resource_path('swagger/appsettings.json'),
                'Ads' => resource_path('swagger/ads.json'),
            ],

            /*
             * The default version that is loaded when the route is accessed.
             */
            'default' => 'v1',

            /*
             * The middleware that is applied to the route.
             */
            'middleware' => [
                'web',
                EnsureUserIsAuthorized::class,
            ],

            /*
             * Specify the validator URL. Set to false to disable validation.
             */
            'validator_url' => false,

            /*
             * If enabled the file will be modified to set the server url and oauth urls.
             */
            'modify_file' => false,

            /*
             * The oauth configuration for the swagger file.
             */
            'oauth' => [
                'token_path' => 'oauth/token',
                'refresh_path' => 'oauth/token',
                'authorization_path' => 'oauth/authorize',

                'client_id' => env('SWAGGER_UI_OAUTH_CLIENT_ID'),
                'client_secret' => env('SWAGGER_UI_OAUTH_CLIENT_SECRET'),
            ],

            /*
             * Path to a custom stylesheet file if you want to customize the look and feel of swagger-ui.
             * The content of the file will be read and added into a style-tag on the swagger-ui page.
             */
            'stylesheet' => null,
        ],
    ],
];
