<x-min-layout>
    <div class="flex relative min-h-screen bg-gray-100 dark:bg-PrimaryBlack">
        @include('dash.layouts.sidebar')
        <section class="container px-4 mx-auto flex-grow-1 pb-32">
            <div class="flex flex-col mt-6 space-y-6">
                <div class="bg-SecondaryBlack rounded-lg p-5">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-SecondaryBlack rounded-lg p-5">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="bg-SecondaryBlack rounded-lg p-5">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-min-layout>
