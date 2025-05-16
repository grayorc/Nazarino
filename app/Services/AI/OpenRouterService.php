<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\AI\AIServiceInterface;

class OpenRouterService implements AIServiceInterface
{
    /**
     * The API key for OpenRouter.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The model to use for completions.
     *
     * @var string
     */
    protected $model;

    /**
     * Default language for summaries.
     *
     * @var string
     */
    protected $language = 'fa';

    /**
     * Create a new OpenRouter service instance.
     *
     * @param string|null $apiKey
     * @param string $model
     */
    public function __construct(string $apiKey = null, string $model = 'meta-llama/llama-4-maverick:free')
    {
        $this->apiKey = $apiKey ?? config('services.openrouter.api_key');
        $this->model = $model;
    }

    /**
     * Set the model to use.
     *
     * @param string $model
     * @return $this
     */
    public function setModel(string $model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get chat completion from OpenRouter API (just for testing).
     * Use php artisan chat {--model=model_name} to test the ai connection
     *
     * @param array $messages
     * @param array $options
     * @return array|null
     */
    public function chatCompletion(array $messages, array $options = [])
    {
        try {
            $defaultParams = config('ai.default_params', [
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            $payload = array_merge(
                $defaultParams,
                [
                    'model' => $this->model,
                    'messages' => $messages,
                ],
                $options
            );

            $timeout = config('ai.timeout', 30);

            $response = Http::withoutVerifying()
                ->timeout($timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://openrouter.ai/api/v1/chat/completions', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OpenRouter API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'model' => $this->model,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OpenRouter Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'model' => $this->model,
            ]);

            return null;
        }
    }

    /**
     * Get a simple response from the AI.
     *
     * @param string $prompt
     * @param array $options
     * @return string|null
     */
    public function ask(string $prompt, array $options = [])
    {
        $messages = [
            [
                'role' => 'user',
                'content' => $prompt,
            ],
        ];

        $response = $this->chatCompletion($messages, $options);

        if (!$response) {
            return null;
        }

        return $response['choices'][0]['message']['content'] ?? null;
    }

    /**
     * Generate a summary of comments for an option.
     *
     * @param array $comments Array of comment objects or strings
     * @param string|null $language Language code (default: fa for Persian)
     * @param array $options Additional options for the AI request
     * @return string|null The generated summary
     */
    public function summarizeComments(array $comments, ?string $language = null, array $options = [])
    {
        if (empty($comments)) {
            return null;
        }

        $lang = $language ?? $this->language;

        $commentTexts = array_map(function ($comment) {
            if (is_string($comment)) {
                return $comment;
            } elseif (is_object($comment) && isset($comment->body)) {
                return $comment->body;
            } elseif (is_array($comment) && isset($comment['body'])) {
                return $comment['body'];
            }
            return '';
        }, $comments);

        // Filter out empty comments
        $commentTexts = array_filter($commentTexts);

        if (empty($commentTexts)) {
            return null;
        }

        $prompt = config('ai.default_prompt');
        $prompt .= implode("\n---\n", $commentTexts);
        $prompt .= config('ai.default_instruction');

        return $this->ask($prompt, $options);
    }

    /**
     * Continue a conversation with the AI.
     *
     * @param array $conversation Previous conversation messages
     * @param string $newMessage New message to add to the conversation
     * @param array $options Additional options for the AI request
     * @return array|null The AI response including the full conversation
     */
    public function continueConversation(array $conversation, string $newMessage, array $options = [])
    {
        $conversation[] = [
            'role' => 'user',
            'content' => $newMessage
        ];

        $response = $this->chatCompletion($conversation, $options);

        if (!$response) {
            return null;
        }

        $aiMessage = $response['choices'][0]['message'] ?? null;
        if ($aiMessage) {
            $conversation[] = $aiMessage;
        }

        return [
            'conversation' => $conversation,
            'response' => $aiMessage['content'] ?? 'No response from AI'
        ];
    }
}
