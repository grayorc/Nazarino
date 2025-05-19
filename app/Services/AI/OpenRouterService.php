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
        $maxRetries = 2;
        $attempt = 0;

        while ($attempt <= $maxRetries) {
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
                        'stream' => false,
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

                Log::warning('OpenRouter API Error (Attempt ' . ($attempt + 1) . '/' . ($maxRetries + 1) . ')', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'model' => $this->model,
                ]);

                // If we get a 429 (rate limit) or 5xx (server error), retry
                if (in_array($response->status(), [429, 500, 502, 503, 504])) {
                    $attempt++;
                    if ($attempt <= $maxRetries) {
                        // Wait before retrying (exponential backoff)
                        $sleepTime = pow(2, $attempt) * 1000; // milliseconds
                        usleep($sleepTime * 1000); // convert to microseconds
                        continue;
                    }
                } else {
                    // For other errors, don't retry
                    break;
                }
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                $isTimeout = strpos($errorMessage, 'timed out') !== false ||
                             strpos($errorMessage, 'Operation timed out') !== false;

                Log::warning('OpenRouter Exception (Attempt ' . ($attempt + 1) . '/' . ($maxRetries + 1) . ')', [
                    'message' => $errorMessage,
                    'is_timeout' => $isTimeout,
                    'model' => $this->model,
                ]);

                // Only retry on timeout errors
                if ($isTimeout) {
                    $attempt++;
                    if ($attempt <= $maxRetries) {
                        // Wait before retrying (exponential backoff)
                        $sleepTime = pow(2, $attempt) * 1000; // milliseconds
                        usleep($sleepTime * 1000); // convert to microseconds
                        continue;
                    }
                } else {
                    // For other exceptions, don't retry
                    break;
                }
            }
        }

        // If we've exhausted all retries or had a non-retryable error
        if ($attempt > 0) {
            Log::error('OpenRouter API failed after ' . $attempt . ' retries', [
                'model' => $this->model,
            ]);
        }

        return null;
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

        // Extract timeout if provided in options
        $timeout = $options['timeout'] ?? null;
        if ($timeout) {
            // Remove from options to avoid duplication in payload
            unset($options['timeout']);
        }

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
    /**
     * Generate an analysis of all comments for an election.
     *
     * @param array $comments Array of comment objects or strings from all options
     * @param array $election Election data for context
     * @param string|null $language Language code (default: fa for Persian)
     * @param array $options Additional options for the AI request
     * @return string|null The generated analysis
     */
    public function summarizeElectionComments(array $comments, array $election, ?string $language = null, array $options = [])
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

        // Create a prompt that includes election context
        $electionTitle = $election['title'] ?? 'نظرسنجی';
        $electionDescription = $election['description'] ?? '';

        $prompt = "تحلیل جامع نظرات برای نظرسنجی '{$electionTitle}':\n\n";
        $prompt .= "توضیحات نظرسنجی: {$electionDescription}\n\n";
        $prompt .= "نظرات کاربران:\n";
        $prompt .= implode("\n---\n", $commentTexts);
        $prompt .= "\n\nلطفاً یک تحلیل جامع و دقیق از نظرات فوق ارائه دهید. نکات مهم، الگوهای تکرار شونده، و دیدگاه‌های متفاوت را مشخص کنید. این تحلیل باید به زبان فارسی و با لحنی رسمی و حرفه‌ای باشد.";

        // Set higher token limit for comprehensive analysis
        $options['max_tokens'] = $options['max_tokens'] ?? 1500;

        return $this->ask($prompt, $options);
    }

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

    public function analyzeElectionWithOptions(array $electionData, ?string $language = null, array $options = [])
    {
        $prompt = "تحلیل جامع نظرسنجی '{$electionData['title']}':\n\n";
        $prompt .= "توضیحات نظرسنجی: {$electionData['description']}\n\n";

        $prompt .= "گزینه‌های نظرسنجی و نظرات کاربران:\n\n";

        foreach ($electionData['options'] as $index => $option) {
            $prompt .= "گزینه #{$index}: {$option['title']}\n";
            $prompt .= "توضیحات: {$option['description']}\n";
            $prompt .= "تعداد آرای مثبت: {$option['upvotes_count']}\n";
            $prompt .= "تعداد آرای منفی: {$option['downvotes_count']}\n\n";

            if (!empty($option['comments'])) {
                $prompt .= "نظرات کاربران برای این گزینه:\n";

                foreach ($option['comments'] as $comment) {
                    $prompt .= "- {$comment['body']}\n";
                }

                $prompt .= "\n";
            } else {
                $prompt .= "هیچ نظری برای این گزینه ثبت نشده است.\n\n";
            }
        }

        $prompt .= "\n\nلطفاً یک تحلیل جامع و دقیق از این نظرسنجی ارائه دهید. تحلیل باید شامل موارد زیر باشد:\n";
        $prompt .= "1. بررسی کلی نظرسنجی و موضوع آن\n";
        $prompt .= "2. تحلیل هر گزینه و نظرات مربوط به آن\n";
        $prompt .= "3. مقایسه گزینه‌ها بر اساس آرا و نظرات\n";
        $prompt .= "4. شناسایی الگوهای تکرار شونده در نظرات\n";
        $prompt .= "5. جمع‌بندی و نتیجه‌گیری کلی\n\n";
        $prompt .= "این تحلیل باید به زبان فارسی و با لحنی رسمی و حرفه‌ای باشد.";
        $prompt .= "درصورت نیاز توصیه به بهبود نظرسنجی ارائه دهید.";

        $options['max_tokens'] = 5000;

        return $this->ask($prompt, $options);
    }
}
