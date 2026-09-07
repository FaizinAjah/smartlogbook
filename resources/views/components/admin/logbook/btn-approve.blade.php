@props(['id'])

<form action="{{ route('admin.log_book.approve', $id) }}" method="POST" class="inline">
    @csrf
    <button type="submit" class="inline-flex items-center justify-center gap-x-1 h-8 px-2.5 text-xs font-semibold rounded-lg border border-green-200 bg-green-50 text-green-600 hover:bg-green-100 focus:outline-none focus:bg-green-100 dark:border-green-800 dark:bg-green-900/20 dark:text-green-500 dark:hover:bg-green-800/30 cursor-pointer" title="Tandai Sudah Direview">
        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        Approve
    </button>
</form>
