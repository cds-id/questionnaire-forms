<x-questionnaire-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
        @if(session('warning'))
            <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                <p>{{ session('warning') }}</p>
            </div>
        @else
            <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h1 class="mt-4 text-2xl font-bold text-gray-900">Thank You!</h1>
            <p class="mt-2 text-gray-600">Your response has been recorded.</p>
        @endif
    </div>
</x-questionnaire-layout>
