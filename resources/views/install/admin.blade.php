@extends('install.layout')

@section('content')
<h2 class="text-xl font-semibold mb-4">Step 3: Create Admin Account</h2>
<p class="text-gray-600 mb-6">Database migrated successfully! Now create the main administrator account.</p>

<form action="{{ route('install.admin.process') }}" method="POST">
    @csrf
    
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Full Name</label>
        <input type="text" name="name" class="w-full border-gray-300 rounded px-4 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Email Address</label>
        <input type="email" name="email" class="w-full border-gray-300 rounded px-4 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Password</label>
        <input type="password" name="password" class="w-full border-gray-300 rounded px-4 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-6">
        <label class="block text-gray-700 font-medium mb-2">Confirm Password</label>
        <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded px-4 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div class="flex justify-end mt-8">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">Create Account & Finish &rarr;</button>
    </div>
</form>
@endsection
