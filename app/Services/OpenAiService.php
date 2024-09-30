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
        $result = $this->openAiClient->chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'user', 'content' => $message],
            ],
        ]);

        return $result->choices[0]->message->content;
    }
}
