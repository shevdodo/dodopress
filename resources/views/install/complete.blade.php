@extends('install.layout')

@section('content')
<div class="text-center">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    </div>
    
    <h2 class="text-2xl font-bold mb-4">Installation Complete!</h2>
    <p class="text-gray-600 mb-8">Dodopress has been successfully installed. You can now login to your dashboard and start managing your site.</p>
    
    <a href="{{ url('/') }}" class="bg-blue-600 text-white px-8 py-3 rounded shadow hover:bg-blue-700 inline-block font-medium">Go to Home</a>
    <a href="{{ route('login') }}" class="bg-gray-800 text-white px-8 py-3 rounded shadow hover:bg-gray-900 inline-block font-medium ml-4">Login to Dashboard</a>
</div>
@endsection
