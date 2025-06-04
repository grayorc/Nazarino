<x-min-layout>
    <div class="flex relative min-h-screen bg-gray-50 dark:bg-PrimaryBlack">
        @include('dash.layouts.sidebar')
        <section class="container px-4 mx-auto flex-grow-1 pb-32">
            <div class="flex flex-col mt-6 space-y-6">
                <div class="bg-white dark:bg-SecondaryBlack rounded-lg p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-white dark:bg-SecondaryBlack rounded-lg p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-min-layout>
