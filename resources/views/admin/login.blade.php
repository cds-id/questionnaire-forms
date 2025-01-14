<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Admin Access') }}
    </div>

    @if ($errors->any())
        <div class="mb-4">
            <div class="font-medium text-red-600">
                {{ __('Whoops! Something went wrong.') }}
            </div>

            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.authenticate') }}">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                         type="password"
                         name="password"
                         required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Login') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
