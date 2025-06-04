<?php

namespace App\Services\AI;

interface AIServiceInterface
{
    public function chatCompletion(array $messages, array $options = []);

    public function ask(string $prompt, array $options = []);

    public function continueConversation(array $conversation, string $newMessage, array $options = []);

    public function summarizeComments(array $comments, ?string $language = null, array $options = []);

    public function analyzeElectionWithOptions(array $electionData, ?string $language = null, array $options = []);
}
