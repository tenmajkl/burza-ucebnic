<?php

declare(strict_types=1);

it('shows login page')
    ->session()
    ->request('login')
    ->assertOK()
    ->assertTemplate('auth.login')
;

it('redirects logged users')
    ->session(email: 'foo@example.com')
    ->request('login')
    ->assertLocation('/')
;
