<?php

// Define app routes

use App\Action\User\UserCreatorAction;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', \App\Action\Home\HomeAction::class)->setName('home');

    // API
    $app->group(
        '/api/users',
        function (RouteCollectorProxy $app) {
            $app->post('', UserCreatorAction::class);
        }
    );
};
