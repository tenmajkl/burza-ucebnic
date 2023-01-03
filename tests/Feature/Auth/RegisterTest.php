<?php

use Lemon\Env;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

it('shows register page')
    ->session()
    ->request('register')
    ->assertOK()
    ->assertTemplate('auth.register')
;

it('redirects logged users')
    ->session(email: 'foo@example.com')
    ->request('register')
    ->assertLocation('/')
;

it('registers user', function() {
    $message = null;
    Env::set('EMAIL', 'example.com');
    Env::set('EMAIL_FROM', 'bar@example.com');
    $this->mock(MailerInterface::class)->expect(send: function($raw_message) use (&$message) {
        $message = $raw_message;
    });
    $this->session();
    $this
        ->request(path: 'register', method: 'POST', body: 'email=foo@example.com&password=foobarbazz', headers: ['Content-Type' => 'application/x-www-form-urlencoded'])
        ->assertOK()
        ->assertTemplate('auth.register', message: 'email-sent')
    ;

    expect($message)->toBeInstanceOf(Email::class);

    expect($message->getTo()[0]->getAddress())->toBe('foo@example.com');

    expect($this->application->get('session')->get('verify_data')['email'])->toBe('foo@example.com');
});
