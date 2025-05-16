<?php

namespace App\Console\Commands;

use App\Facades\AI;
use Illuminate\Console\Command;

use function Laravel\Prompts\textarea;
use function Laravel\Prompts\info;

class ChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat {--model=deepseek/deepseek-chat : The model to use for chat completion}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chat with an AI';

    /**
     * The model to use for chat completion.
     *
     * @var string
     */
    protected $model;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->model = $this->option('model') ?? config('ai.default_model');

        $this->info('Starting chat session with AI. Type \'exit\' to end the conversation.');
        $this->info('Using model: ' . $this->model);

        $conversation = [];

        while (true) {
            $userMessage = textarea('You');

            if (strtolower(trim($userMessage)) === 'exit') {
                $this->info('Ending chat session.');
                break;
            }

            $result = AI::continueConversation($conversation, $userMessage, [
                'model' => $this->model
            ]);

            if (!$result || !isset($result['response'])) {
                $this->error('Failed to get response from AI.');
                continue;
            }

            $conversation = $result['conversation'];

            $this->info('AI: ' . $result['response']);
        }

        return 0;
    }
}
