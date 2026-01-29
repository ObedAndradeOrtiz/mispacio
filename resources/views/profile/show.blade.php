<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="/jr/dashboard" class="flex items-center">
                <img alt="Logo" src="{{ asset('logo.jpg') }}" class="object-cover w-10 h-10 rounded-full" />
                <h1 class="ml-2 text-xl font-bold text-dark">Spa Miora</h1>
            </a>

            <h2 class="ml-6 text-lg font-semibold text-gray-800">
                {{ __('Perfil General') }}
            </h2>
        </div>
    </x-slot>


    <div>
        <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @livewire('perfil-usuario')
            <div style="margin-bottom: 5%;">

            </div>
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')
                <div style="margin-bottom: 5%;">

                </div>
                {{-- <x-section-border /> --}}
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <div style="margin-bottom: 5%;">

                </div>
            @endif


            {{-- @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif --}}

            {{-- <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div> --}}

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                {{-- <x-section-border /> --}}

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
