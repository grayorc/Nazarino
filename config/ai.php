<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Analysis Enable/Disable
    |--------------------------------------------------------------------------
    |
    | This value determines whether the AI analysis feature is enabled or not.
    |
    */
    'enabled' => env('AI_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | AI Analysis Timeout
    |--------------------------------------------------------------------------
    |
    | This value determines how many minutes must pass before allowing
    | a new AI analysis for the same election.
    |
    */
    'analysis_timeout' => env('AI_ANALYSIS_TIMEOUT', 30),
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
    'timeout' => env('AI_REQUEST_TIMEOUT', 60),
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
    'default_prompt' => env('AI_DEFAULT_PROMPT',
        'لطفاً خلاصه‌ای از نظرات زیر ارائه دهید. نکات کلیدی، موضوعات مشترک و احساسات کلی را برجسته کنید. فقط نظرات مرتبط و قابل درک را تحلیل کنید. نظرات غیرمرتبط یا بی‌معنی را نادیده بگیرید و در خلاصه‌ی ارائه‌شده به هیچ‌وجه از آن‌ها نام نبرید.'
        . '\n\n'),
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
    'default_instruction' => env('AI_DEFAULT_INSTRUCTION',
        'لطفاً خلاصه را به صورت پاراگراف-های کوتاه و مختصر ارائه دهید. از نقل قول مستقیم استفاده نکنید. فقط خلاصه را بنویسید، بدون مقدمه یا توضیح اضافی.'
        . '\n\n'
        . '\n'
        . '- شناسایی موضوعات مشترک و دیدگاه‌های تکرارشونده'
        . '\n'
        . '- تحلیل احساسات (مثبت، منفی، خنثی)'
        . '\n'
        . '- بررسی تأثیر احتمالی نظرات بر نتیجه انتخابات'
        . '\n'
        . '- حذف موارد غیرمرتبط یا بی‌معنی از تحلیل بدون ذکر آن‌ها در خلاصه'
    ),

];
