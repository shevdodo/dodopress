<div class="bg-white border border-gray-200 rounded-lg shadow-sm group mb-3" data-id="{{ $item->id }}">
    <div class="p-4 flex items-center justify-between cursor-move">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
            <div>
                <p class="font-semibold text-gray-800">{{ $item->title }}</p>
                <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">{{ $item->type }} @if($item->type == 'custom') ({{ $item->url }}) @endif</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" 
                    onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->title) }}', '{{ $item->url }}', '{{ $item->type }}')"
                    class="text-blue-500 hover:text-blue-700 p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
            </button>
            <form action="{{ route('superuser.menus.items.destroy', $item) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 p-2" onclick="return confirm('Delete this item?')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </form>
        </div>
    </div>
    <div class="pl-8 pr-4 pb-4 nested-sortable min-h-[10px] {{ $item->children->count() > 0 ? '' : 'empty-container' }}">
        @foreach($item->children as $child)
            @include('dashboard.menus.partials.item', ['item' => $child])
        @endforeach
    </div>
</div>
