<x-questionnaire-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $questionnaire->title }}</h1>
        @if($questionnaire->description)
            <p class="mt-4 text-gray-600">{{ $questionnaire->description }}</p>
        @endif
    </div>

    <form method="POST" action="{{ route('questionnaire.submit', $questionnaire) }}">
        @csrf

        @foreach($questionnaire->sections as $section)
            @if($section->title)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $section->title }}</h2>
                    @if($section->description)
                        <p class="mt-2 text-gray-600">{{ $section->description }}</p>
                    @endif
                </div>
            @endif

            @foreach($section->questions as $question)
                @if($question->type === 'radio')
                    <x-questionnaire.radio-group
                        :question="$question"
                        :value="old('answers.' . $question->id)"
                    />
                @elseif($question->type === 'textarea')
                    <x-questionnaire.textarea
                        :question="$question"
                        :value="old('answers.' . $question->id)"
                    />
                @endif
            @endforeach
        @endforeach

        <div class="flex justify-end mt-6">
            <x-primary-button>
                {{ __('Submit') }}
            </x-primary-button>
        </div>
    </form>

    @if ($errors->any())
        <div class="mt-6">
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            {{ __('There were errors with your submission') }}
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-questionnaire-layout>
