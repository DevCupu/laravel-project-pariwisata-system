<?php

it('renders public pages', function (string $path) {
    $this->get($path)->assertOk();
})->with([
    '/',
    '/aktivitas',
    '/menu-kafe',
    '/booking',
    '/booking/status',
    '/events',
    '/kontak',
]);
