@extends('install.layout')

@section('content')
<h2 class="text-xl font-semibold mb-4">Step 1: Server Requirements</h2>

<div class="mb-6">
    <ul class="space-y-3">
        @foreach($requirements as $name => $passed)
        <li class="flex items-center justify-between border-b pb-2">
            <span class="text-gray-700">{{ $name }}</span>
            @if($passed)
                <span class="text-green-600 font-bold">Passed</span>
            @else
                <span class="text-red-600 font-bold">Failed</span>
            @endif
        </li>
        @endforeach
    </ul>
</div>

<div class="flex justify-end mt-8">
    @if($allPassed)
        <a href="{{ route('install.database') }}" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">Next Step &rarr;</a>
    @else
        <button disabled class="bg-gray-400 text-white px-6 py-2 rounded shadow cursor-not-allowed">Fix Requirements to Continue</button>
    @endif
</div>
@endsection
