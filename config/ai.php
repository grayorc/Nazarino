<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Model
    |--------------------------------------------------------------------------
    |
    | This value determines which AI model will be used by default when no
    | specific model is specified. You can change this to any model supported
    | by your AI service provider.
    |
    */
    'default_model' => env('AI_DEFAULT_MODEL', 'meta-llama/llama-4-maverick:free'),

    /*
    |--------------------------------------------------------------------------
    | Default Parameters
    |--------------------------------------------------------------------------
    |
    | These are the default parameters that will be sent with each request
    | to the AI service. You can override these parameters when making
    | individual requests.
    |
    */
    'default_params' => [
        'temperature' => 0.7,
        'max_tokens' => 1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | This value determines the maximum number of seconds to wait for a response
    | from the AI service before timing out.
    |
    */
    'timeout' => env('AI_REQUEST_TIMEOUT', 30),
    /*
    |--------------------------------------------------------------------------
    | Default Prompt
    |--------------------------------------------------------------------------
    |
    | This value determines the default prompt that will be used by default when no
    | specific prompt is specified. You can change this to any prompt supported
    | by your AI service provider.
    |
    */
    'default_prompt' => env('AI_DEFAULT_PROMPT', 'لطفاً خلاصه‌ای از نظرات زیر ارائه دهید. نکات کلیدی، موضوعات مشترک و احساسات کلی را برجسته کنید:\n\n'),
    /*
    |--------------------------------------------------------------------------
    | Default Instruction
    |--------------------------------------------------------------------------
    |
    | This value determines the default instruction that will be used by default when no
    | specific instruction is specified. You can change this to any instruction supported
    | by your AI service provider.
    |
    */
    'default_instruction' => env('AI_DEFAULT_INSTRUCTION', 'لطفاً خلاصه را به صورت پاراگراف-های کوتاه و مختصر ارائه دهید. از نقل قول مستقیم استفاده نکنید. فقط خلاصه را بنویسید، بدون مقدمه یا توضیح اضافی.'),
];
