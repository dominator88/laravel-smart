<?php

Route::group([
    'prefix'        => config('backend.route.prefix'),
    'namespace'     => 'Tests\Controllers',
    'middleware'    => ['web', 'admin'],
], function ($router) {

});