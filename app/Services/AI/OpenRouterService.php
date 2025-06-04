<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\AI\AIServiceInterface;

class OpenRouterService implements AIServiceInterface
{
    protected $apiKey;
    protected $model;
    protected $language = 'fa';

    public function __construct(string $apiKey = null, string $model = 'meta-llama/llama-4-maverick:free')
    {
        $this->apiKey = $apiKey ?? config('services.openrouter.api_key');
        $this->model = $model;
    }

    public function setModel(string $model)
    {
        $this->model = $model;
        return $this;
    }

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
            } catch (\Exception $e) {
                $attempt++;
                $errorMessage = $e->getMessage();
                Log::error('OpenRouter API Exception (Attempt ' . $attempt . '/' . ($maxRetries + 1) . ')', [
                    'message' => $errorMessage,
                    'model' => $this->model,
                ]);
            }
        }
        return null;
    }

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

    // for test
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
