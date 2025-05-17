
<x-min-layout>
    <div x-data="{
        dropdownOpen: false
    }"
         class="relative">
        @include('dash.layouts.sidebar')
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        <section class="container px-2 md:px-4 mx-auto flex-grow-1 w-full">
            @include('layouts.subs')
        </section>
        </div>
    </div>
</x-min-layout>
