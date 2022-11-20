<?php

it('has a homepage')
    ->request('/')
    ->assertOK()
;
