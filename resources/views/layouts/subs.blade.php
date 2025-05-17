<div class="mx-auto mt-16 grid max-w-xl grid-cols-1 items-center gap-y-6 gap-x-6 sm:mt-20 sm:gap-y-6 lg:max-w-6xl lg:grid-cols-3 lg:gap-x-8">
    @foreach($subscriptionTiers as $tier)
        <div class="@if($tier->price > 0) relative rounded-3xl bg-PrimaryBlack shadow-2xl @else rounded-3xl rounded-t-3xl bg-white/60 dark:bg-PrimaryBlack/80 dark:ring-gray-700/30 @endif p-8 ring-1 ring-gray-900/10 sm:p-10 h-full w-full sm:w-80 md:w-96 mx-auto">
            <h3 id="tier-{{ Str::slug($tier->title) }}" class="text-base/7 font-semibold text-primaryColor">
                {{ $tier->title }}
            </h3>

            <p class="mt-4 flex items-baseline gap-x-2">
            <span class="text-5xl font-semibold tracking-tight @if($tier->price > 0) text-white @else text-gray-900 dark:text-primaryWhite @endif">
                @if($tier->price > 0)
                    {{ number_format($tier->price) }}ت
                @else
                    رایگان
                @endif
            </span>
                <span class="text-base @if($tier->price > 0) text-gray-400 @else text-gray-500 dark:text-gray-400 @endif">
                {{ $tier->price > 0 ? '/ماهیانه' : '' }}
            </span>
            </p>

            <p class="mt-6 text-base/7 @if($tier->price > 0) text-gray-300 @else text-gray-600 dark:text-gray-300 @endif">
                {{ $tier->price > 0 ? 'پلن ماهیانه بدون محدودیت' : 'پلن رایگان با محدودیت' }}
            </p>

            <ul role="list" class="mt-8 space-y-3 text-sm/6 @if($tier->price > 0) text-gray-300 @else text-gray-600 dark:text-gray-300 @endif sm:mt-10">
                @foreach($tier->subFeatures->sortBy('sort_order') as $feature)
                    <li class="flex gap-x-3">
                        <svg class="h-6 w-5 flex-none text-primaryColor" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                        </svg>
                        {{ $feature->name }}
                    </li>
                @endforeach
            </ul>
            <div class="mt-auto">
                <a href="{{ route('purchase.index', $tier) }}"
                   aria-describedby="tier-{{ Str::slug($tier->title) }}"
               class="mt-auto block rounded-md  @if($tier->price > 0)
                    bg-primaryColor text-white hover:bg-PrimaryBlack border-primaryColor border-2 hover:border-primaryColor hover:text-primaryColor
                @else
                    text-primaryColor ring-1 ring-indigo-200 ring-inset hover:ring-indigo-300
                @endif px-3.5 py-2.5 text-center text-sm font-semibold transition delay-100 duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primaryColor sm:mt-10">
                    همین الان شروع کن!
                </a>
            </div>
        </div>
    @endforeach
</div>
