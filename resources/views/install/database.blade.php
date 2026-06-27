@extends('install.layout')

@section('content')
<h2 class="text-xl font-semibold mb-4">Step 2: Database Configuration</h2>
<p class="text-gray-600 mb-6">Enter your database connection details below.</p>

<form action="{{ route('install.database.process') }}" method="POST">
    @csrf
    
    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Database Host</label>
        <input type="text" name="db_host" value="127.0.0.1" class="w-full border-gray-300 rounded px-4 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Database Port</label>
        <input type="text" name="db_port" value="3306" class="w-full border-gray-300 rounded px-4 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Database Name</label>
        <input type="text" name="db_database" placeholder="dodopress" class="w-full border-gray-300 rounded px-4 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2">Database Username</label>
        <input type="text" name="db_username" value="root" class="w-full border-gray-300 rounded px-4 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div class="mb-6">
        <label class="block text-gray-700 font-medium mb-2">Database Password</label>
        <input type="password" name="db_password" class="w-full border-gray-300 rounded px-4 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-500">
        <small class="text-gray-500">Leave blank if no password.</small>
    </div>

    <div class="flex justify-between mt-8">
        <a href="{{ route('install.requirements') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2">&larr; Back</a>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">Save & Migrate &rarr;</button>
    </div>
</form>
@endsection
