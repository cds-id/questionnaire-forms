<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-4">
    <div class="mb-4">
        <label class="block text-lg font-medium text-gray-900">
            {{ $question->title }}
            @if($question->is_required)
                <span class="text-red-500">*</span>
            @endif
        </label>

        @if($question->description)
            <p class="mt-1 text-sm text-gray-600">{{ $question->description }}</p>
        @endif
    </div>

    <div class="space-y-3">
        @foreach($question->options as $option)
            <div class="flex items-center">
                <input type="radio"
                       id="question_{{ $question->id }}_{{ $loop->index }}"
                       name="answers[{{ $question->id }}]"
                       value="{{ $option }}"
                       @if($value === $option) checked @endif
                       @required($question->is_required)
                       class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                <label for="question_{{ $question->id }}_{{ $loop->index }}"
                       class="ml-3 block text-sm font-medium text-gray-700">
                    {{ $option }}
                </label>
            </div>
        @endforeach
    </div>
</div>
