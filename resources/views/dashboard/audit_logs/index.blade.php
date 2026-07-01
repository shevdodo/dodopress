<x-layouts.dashboard title="Audit Logs">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Audit Logs</h2>
            <p class="text-sm text-gray-500 mt-1">
                View system activity and user actions.
            </p>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Action</th>
                        <th class="px-6 py-4 font-semibold">Model</th>
                        <th class="px-6 py-4 font-semibold">Details</th>
                        <th class="px-6 py-4 font-semibold">IP Address</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->user)
                                    <div class="flex items-center space-x-4">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-100 to-brand-200 flex items-center justify-center text-brand-700 font-bold uppercase shrink-0">
                                            {{ substr($log->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $log->user->name }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-500 italic">System / Unknown</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $log->action }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                @if($log->model_type)
                                    {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate" title="{{ json_encode($log->details) }}">
                                @if($log->details)
                                    <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">{{ json_encode($log->details) }}</code>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-xs">
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No audit logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>
