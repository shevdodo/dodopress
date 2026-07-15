<x-layouts.dashboard title="User Management">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">All Registered Users</h2>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Search users..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/50 bg-gray-50/50 min-w-[200px]">
            </div>
            <div class="text-sm text-gray-500 font-medium">
                {{ $users->total() }} total
            </div>
            <a href="{{ route('superuser.users.create') }}" class="bg-brand-600 text-white px-4 py-2 rounded-lg font-medium shadow-md hover:bg-brand-700 transition flex items-center space-x-1.5 hidden sm:flex">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Add User</span>
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium flex items-center space-x-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm font-medium">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-bold uppercase tracking-wider text-gray-500">
                        <th class="px-6 py-5 w-10">
                            <input type="checkbox" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        </th>
                        <th class="px-4 py-5 font-bold">User</th>
                        <th class="px-6 py-5 font-bold hidden md:table-cell">Email</th>
                        <th class="px-6 py-5 font-bold hidden sm:table-cell">Role</th>
                        <th class="px-6 py-5 font-bold hidden sm:table-cell">Joined</th>
                        <th class="px-6 py-5 font-bold text-right hidden sm:table-cell">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-full {{ $user->role === 'superuser' ? 'bg-[#c87652] text-white' : 'bg-[#c87652] text-white' }} flex items-center justify-center font-bold uppercase shrink-0">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div class="w-full">
                                        <div class="flex justify-between items-start sm:block">
                                            <div>
                                                <div class="flex items-center space-x-2">
                                                    <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                                    <div class="sm:hidden">
                                                        @if($user->role === 'superuser')
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider bg-red-100 text-red-600 uppercase">Superuser</span>
                                                        @elseif($user->role === 'admin')
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider bg-blue-100 text-blue-600 uppercase">Admin</span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider bg-green-100 text-green-600 uppercase">User</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <p class="text-gray-500 text-sm truncate max-w-[150px] md:hidden">{{ $user->email }}</p>
                                            </div>
                                            <div class="sm:hidden text-right">
                                                <span class="text-[10px] text-gray-400">{{ $user->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>

                                        <!-- Mobile Actions -->
                                        <div class="mt-3 sm:hidden flex items-center space-x-4 text-xs font-medium pt-2 border-t border-gray-100">
                                            <a href="{{ route('superuser.users.edit', $user) }}" class="text-gray-400 hover:text-indigo-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </a>
                                            @if($user->id !== Auth::id())
                                                <form action="{{ route('superuser.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-600">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-sm hidden md:table-cell">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                @if($user->role === 'superuser')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold tracking-wider bg-red-100 text-red-600 uppercase">
                                        Superuser
                                    </span>
                                @elseif($user->role === 'admin')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold tracking-wider bg-blue-100 text-blue-600 uppercase">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold tracking-wider bg-emerald-100 text-emerald-600 uppercase">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-sm hidden sm:table-cell">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium hidden sm:table-cell">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('superuser.users.edit', $user) }}" class="text-gray-400 hover:text-indigo-600 transition" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('superuser.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="w-5 h-5 text-transparent"></span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>
