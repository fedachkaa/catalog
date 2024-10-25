<?php

namespace App\Services;

use OpenAI;

class OpenAiService
{
    /** @var OpenAI */
    private $openAiClient;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->openAiClient = OpenAI::client(getenv('OPENAI_API_KEY'));
    }

    /**
     * @param string $message
     * @return string|null
     */
    public function sendRequest(string $message): ?string
    {
        $requestText = 'Згенеруй 5 тем (українською мовою) для кваліфікаційних робіт за ключовим словом "' . $message . '", розділивши кожну тему переносом рядка без нумерації';

        $result = $this->openAiClient->chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'user', 'content' => $requestText],
            ],
        ]);

        return $result->choices[0]->message->content;
    }
}
