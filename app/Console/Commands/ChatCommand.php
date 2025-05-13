<?php

namespace App\Console\Commands;

use Generator;
use Illuminate\Console\Command;

use function Laravel\Prompts\textarea;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;

class ChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chat with AI';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $message = textarea('Message:');
        $prism = $this->getPrism();
        $response = $prism->withPrompt($message)->generate();
        dd($response->text);
        return $response;
    }

    protected function getPrism()
    {
        $prism = Prism::text()
        ->using(Provider::Ollama, 'meta-llama/llama-3.3-70b-instruct:free');

        return $prism;
    }
}
