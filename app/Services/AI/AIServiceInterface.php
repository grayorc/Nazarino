<?php

namespace App\Services\AI;

interface AIServiceInterface
{
    public function chatCompletion(array $messages, array $options = []);

    /**
     * Get a simple response from the AI.
     *
     * @param string $prompt
     * @param array $options
     * @return strin|null
     */
    public function ask(string $prompt, array $options = []);

    /**
     * Continue a conversation with the AI.
     *
     * @param array $conversation
     * @param string $newMessage
     * @param array $options
     * @return array|null
     */
    public function continueConversation(array $conversation, string $newMessage, array $options = []);

    /**
     * Generate a summary of comments for an option.
     *
     * @param array $comments Array of comment objects or strings
     * @param string|null $language Language code
     * @param array $options Additional options for the AI request
     * @return string|null The generated summary
     */
    public function summarizeComments(array $comments, ?string $language = null, array $options = []);

    /**
     * Generate an analysis of all comments for an election.
     *
     * @param array $comments Array of comment objects or strings from all options
     * @param array $election Election data for context
     * @param string|null $language Language code
     * @param array $options Additional options for the AI request
     * @return string|null The generated analysis
     */
    public function summarizeElectionComments(array $comments, array $election, ?string $language = null, array $options = []);
    
    /**
     * Analyze election data with options and their comments
     *
     * @param array $electionData Complete election data with options and comments
     * @param string|null $language Language code
     * @param array $options Additional options for the AI request
     * @return string|null The generated comprehensive analysis
     */
    public function analyzeElectionWithOptions(array $electionData, ?string $language = null, array $options = []);
}
