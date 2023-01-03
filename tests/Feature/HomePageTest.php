<?php

declare(strict_types=1);

it('has a homepage')
    ->request('/')
    ->assertOK()
;
