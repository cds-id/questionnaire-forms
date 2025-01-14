@props(['question', 'value' => null])

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

    <textarea
        name="answers[{{ $question->id }}]"
        rows="4"
        @required($question->is_required)
        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        placeholder="Your answer">{{ $value }}</textarea>
</div>
