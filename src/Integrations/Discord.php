<?php

declare(strict_types=1);

namespace App\Integrations;

use App\Contracts\Discord as DiscordContract;
use App\Entities\Offer;
use App\Entities\User;
use Lemon\Kernel\Application;
use Lemon\Support\Env;

class Discord implements DiscordContract
{
    public function __construct(
        public readonly Env $env,
        public readonly Application $app,
    ) {
    }

    public function sendWebhook(array $message): bool
    {
        $ch = curl_init($this->env->get('DISCORD_WEBHOOK_URL'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        curl_exec($ch);
        curl_close($ch);

        return 200 === curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }

    public function sendIssue(string $description, User $author): bool
    {
        return $this->sendWebhook([
            'embeds' => [
                [
                    'title' => 'Nová zpětná vazba <:kanec_exploze:1107056283003142166>',
                    'description' => $description,
                    'color' => 0xFFFF00,
                    'timestamp' => date('c'),
                    'author' => [
                        'name' => $author->email.'@'.$author->year->school->email,
                    ],
                ],
            ],
        ]);
    }
    
    public function sendRequest(string $email, string $school): bool
    {
        return $this->sendWebhook([
            'embeds' => [
                [
                    'title' => 'Škola má zájem o burzu! <:kanec_exploze:1107056283003142166>',
                    'description' => $email,
                    'color' => 0x00FF00,
                    'timestamp' => date('c'),
                    'author' => [
                        'name' => $school,
                    ],
                ],
            ],
        ]);
    }

    public function sendOffer(Offer $offer): bool
    {
        return $this->sendWebhook([
            'embeds' => [
                [
                    'title' => 'Nova objednavka',
                    'color' => 0x396284,
                    'description' => '@'.$offer->user->email.' tak to pls checkni dik',
                ],
            ],
        ]);
    }

    public function sendSuccess(): bool
    {
        return $this->sendWebhook([
            'embeds' => [
                [
                    'title' => 'USPESNA OBJEDNAVKA I REPEAT USPESNA OBJEDNAVKA MELO TO SMYSL KLUCI <:kanec_exploze:1107056283003142166> <:kanec_exploze:1107056283003142166> <:kanec_exploze:1107056283003142166>',
                    'color' => 0x00FF00,
                    'image' => [
                        'url' => 'https://media1.tenor.com/m/XCReBZW8JFAAAAAd/cr1ti-ka-l-penguinz0.gif',
                    ]
                ],
            ],
        ]);
    }
}
