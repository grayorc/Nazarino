<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;

class AiController extends Controller
{
    public function generate()
    {
        $prism = Prism::text()
        ->using('openrouter', 'llama-4-maverick:free')
        ->withSystemPrompt(view('prompts.nyx'))
        ->withPrompt('Explain quantum computing to a 5-year-old.')
        ->asText();

        return $prism->run();
    }
}
