@extends('_admin._layout.app')

@section('title', $page['title'])

@section('content')
    <x-admin.page-header :title="'Data ' . $page['title']" subtitle="Catatan aktivitas harian">
        <div class="flex gap-2">
            <x-admin.button href="{{ route('admin.log_book.export_excel', ['keywords' => $keywords, 'month' => $month, 'year' => $year, 'user_id' => $user_id ?? '', 'filter_status' => request('filter_status')]) }}" color="success" class="font-bold">
                @include('_admin._layout.icons.excel')
                Export Excel
            </x-admin.button>
            <x-admin.button href="{{ route('admin.log_book.export_pdf', ['keywords' => $keywords, 'month' => $month, 'year' => $year, 'user_id' => $user_id ?? '', 'filter_status' => request('filter_status')]) }}" color="danger" class="font-bold">
                @include('_admin._layout.icons.pdf')
                Export PDF
            </x-admin.button>
            @if(\Illuminate\Support\Facades\Auth::user()?->access_type == \App\Constants\UserConst::ANGGOTA)
            <x-admin.button href="{{ route('admin.log_book.add') }}" class="font-bold">
                @include('_admin._layout.icons.add')
                Tambah Data
            </x-admin.button>
            @endif
        </div>
    </x-admin.page-header>

    <div class="mb-6">
        <form action="{{ route('admin.log_book.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row items-center gap-3">
            <div class="w-full sm:w-64">
                <x-admin.input :label="null" name="keywords" :value="$keywords ?? ''" placeholder="Judul atau deskripsi" size="sm" />
            </div>
            <div class="w-full sm:w-48">
                <x-admin.select :label="null" name="month" :options="$monthOptions" :value="$month ?? ''" size="sm" class="cursor-pointer" placeholder="Semua Bulan" />
            </div>
            <div class="w-full sm:w-48">
                <x-admin.select :label="null" name="year" :options="$yearOptions" :value="$year ?? ''" size="sm" class="cursor-pointer" placeholder="Semua Tahun" />
            </div>
            <div class="w-full sm:w-48">
                <x-admin.select :label="null" name="filter_status" :options="$filterStatusOptions ?? \App\Constants\LogBookConst::getFilterStatusOptions()" :value="request('filter_status')" size="sm" class="cursor-pointer" placeholder="Semua Filter" />
            </div>
            @if(in_array(\Illuminate\Support\Facades\Auth::user()?->access_type, [\App\Constants\UserConst::SUPERADMIN, \App\Constants\UserConst::PEMBIMBING]))
            <div class="w-full sm:w-48">
                <x-admin.select :label="null" name="user_id" :options="$userOptions" :value="$user_id ?? ''" size="sm" class="cursor-pointer" placeholder="Semua Anggota" />
            </div>
            @endif
            <div class="flex items-center gap-2">
                <x-admin.button type="submit" size="sm" color="primary">
                    @include('_admin._layout.icons.search')
                    Cari
                </x-admin.button>
                @if (!empty($keywords) || !empty($month) || !empty($year) || !empty($user_id) || !empty(request('filter_status')))
                    <x-admin.button href="{{ route('admin.log_book.index') }}" size="sm" color="outline-secondary">
                        @include('_admin._layout.icons.reset')
                        Reset
                    </x-admin.button>
                @endif
            </div>
        </form>
    </div>

    <x-admin.table.wrapper>
        <x-admin.table>
            <x-admin.table.thead>
                <tr>
                    <x-admin.table.th class="w-px whitespace-nowrap">Tanggal</x-admin.table.th>
                    @if(in_array(\Illuminate\Support\Facades\Auth::user()?->access_type, [\App\Constants\UserConst::SUPERADMIN, \App\Constants\UserConst::PEMBIMBING]))
                        <x-admin.table.th class="w-px whitespace-nowrap">Pembuat</x-admin.table.th>
                    @endif
                    <x-admin.table.th>Judul</x-admin.table.th>
                    <x-admin.table.th class="w-px whitespace-nowrap">Kehadiran</x-admin.table.th>
                    <x-admin.table.th class="w-px whitespace-nowrap">Status</x-admin.table.th>
                    <x-admin.table.th align="end" class="w-px whitespace-nowrap"></x-admin.table.th>
                </tr>
            </x-admin.table.thead>
            <x-admin.table.tbody>
                @forelse($data as $d)
                    @php
                        $isWeekend = !empty($d->is_weekend);
                        $isHoliday = !empty($d->is_holiday);
                        $isEmpty = !empty($d->is_empty);
                        $authAccess = \Illuminate\Support\Facades\Auth::user()?->access_type;
                        $isSuperadminView = $authAccess == \App\Constants\UserConst::SUPERADMIN;
                        $isPembimbingView = $authAccess == \App\Constants\UserConst::PEMBIMBING;
                        $isAnggotaView = $authAccess == \App\Constants\UserConst::ANGGOTA;
                        
                        $rowClass = '';
                        if ($isHoliday || $isWeekend) {
                            $rowClass = 'bg-red-50 dark:bg-red-900/20';
                        } elseif ($isEmpty) {
                            $rowClass = 'bg-yellow-50 dark:bg-yellow-900/20';
                        }
                    @endphp
                    <x-admin.table.tr class="{{ $rowClass }}">
                        <x-admin.table.td>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium {{ $isHoliday || $isWeekend ? 'text-red-800 dark:text-red-200' : 'text-gray-800 dark:text-neutral-200' }}">
                                    {{ $d->log_date ? \Carbon\Carbon::parse($d->log_date)->translatedFormat('l, d M Y') : '-' }}
                                </span>
                                @if (!$isEmpty && !empty($d->created_at))
                                    <span class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5 flex items-center gap-1" title="Waktu Pengumpulan">
                                        <svg class="size-3 text-gray-400 dark:text-neutral-500 inline-block shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($d->created_at)->timezone(config('app.timezone', 'Asia/Jakarta'))->format('H:i') }} WIB
                                    </span>
                                @endif
                            </div>
                        </x-admin.table.td>
                        @if(in_array(\Illuminate\Support\Facades\Auth::user()?->access_type, [\App\Constants\UserConst::SUPERADMIN, \App\Constants\UserConst::PEMBIMBING]))
                        <x-admin.table.td>
                            <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $d->user_name ?? '-' }}</span>
                        </x-admin.table.td>
                        @endif
                        <x-admin.table.td class="!whitespace-normal min-w-[16rem]">
                            <div class="flex flex-col gap-1">
                                @if ($isEmpty)
                                    @if ($isHoliday)
                                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">Libur Nasional: {{ $d->title }}</span>
                                    @elseif ($isWeekend)
                                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">Hari Libur</span>
                                    @else
                                        <span class="text-sm font-semibold text-gray-400 dark:text-neutral-500 italic">Belum diisi</span>
                                    @endif
                                @else
                                    <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $d->title }}</span>
                                @endif
                            </div>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            @if (!$isEmpty)
                                <x-admin.logbook.badge-attendance :status="$d->attendance_status ?? null" />
                            @endif
                        </x-admin.table.td>
                        <x-admin.table.td>
                            @if (!$isEmpty)
                                <x-admin.logbook.badge-status :status="$d->status ?? null" />
                            @elseif (!$isHoliday && !$isWeekend)
                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400">Belum Diisi</span>
                            @elseif ($isHoliday || $isWeekend)
                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">Hari Libur</span>
                            @endif
                        </x-admin.table.td>
                        <x-admin.table.td innerClass="px-6 py-1.5 flex items-center justify-end gap-x-1">
                            @if ($isEmpty)
                                @if ($isAnggotaView)
                                    <a navigate
                                        class="inline-flex items-center justify-center h-8 px-3 text-sm font-semibold rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:border-blue-300 focus:outline-none focus:bg-blue-100 disabled:opacity-50 disabled:pointer-events-none dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-500 dark:hover:bg-blue-800/30 dark:hover:border-blue-700"
                                        href="{{ route('admin.log_book.add', ['date' => $d->log_date]) }}" title="Isi Logbook">
                                        Isi Logbook
                                    </a>
                                @endif
                            @else
                                @if ($isPembimbingView)
                                    <a navigate
                                        class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700"
                                        href="{{ route('admin.log_book.detail', $d->id) }}" title="Lihat Detail">
                                        @include('_admin._layout.icons.view_detail')
                                    </a>
                                @else
                                    @if ($isSuperadminView && !in_array(strtolower($d->status ?? ''), ['sudah_direview', 'sudah direview']))
                                        <x-admin.logbook.btn-approve :id="$d->id" />
                                    @endif
                                    <a navigate
                                        class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700"
                                        href="{{ route('admin.log_book.detail', $d->id) }}" title="Lihat Detail">
                                        @include('_admin._layout.icons.view_detail')
                                    </a>
                                    <a navigate
                                        class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:border-blue-300 focus:outline-none focus:bg-blue-100 disabled:opacity-50 disabled:pointer-events-none dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-500 dark:hover:bg-blue-800/30 dark:hover:border-blue-700"
                                        href="{{ route('admin.log_book.update', $d->id) }}" title="Edit">
                                        @include('_admin._layout.icons.pencil')
                                    </a>
                                    <button type="button"
                                        class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 hover:border-red-300 focus:outline-none focus:bg-red-100 disabled:opacity-50 disabled:pointer-events-none dark:border-red-800 dark:bg-red-900/20 dark:text-red-500 dark:hover:bg-red-800/30 dark:hover:border-red-700 cursor-pointer"
                                        title="Delete" data-hs-overlay="#delete-modal"
                                        onclick='setDeleteData({{ $d->id }}, @json($d->title))'>
                                        @include('_admin._layout.icons.trash')
                                    </button>
                                @endif
                            @endif
                        </x-admin.table.td>
                    </x-admin.table.tr>
                @empty
                    <tr>
                        <td colspan="{{ in_array(\Illuminate\Support\Facades\Auth::user()?->access_type, [\App\Constants\UserConst::SUPERADMIN, \App\Constants\UserConst::PEMBIMBING]) ? 6 : 5 }}" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-neutral-500">
                            <x-admin.empty-state />
                        </td>
                    </tr>
                @endforelse
            </x-admin.table.tbody>
        </x-admin.table>
        @if (count($data) > 0 && is_object($data) && method_exists($data, 'hasPages') && $data->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
                <div class="flex justify-end">
                    {{ $data->links() }}
                </div>
            </div>
        @endif
    </x-admin.table.wrapper>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto"
        role="dialog" tabindex="-1" aria-labelledby="delete-modal-label">
        <div
            class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
            <div
                class="relative flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
                <div class="absolute top-2 end-2">
                    <button type="button"
                        class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600"
                        aria-label="Close" data-hs-overlay="#delete-modal">
                        <span class="sr-only">Close</span>
                        @include('_admin._layout.icons.close_modal')
                    </button>
                </div>

                <div class="p-4 sm:p-10 text-center overflow-y-auto">
                    <span
                        class="mb-4 inline-flex justify-center items-center size-14 rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100">
                        @include('_admin._layout.icons.warning_modal')
                    </span>

                    <h3 id="delete-modal-label" class="mb-2 text-xl font-bold text-gray-800 dark:text-neutral-200">
                        Hapus Log Book
                    </h3>
                    <p class="text-gray-500 dark:text-neutral-500">
                        Apakah Anda yakin ingin menghapus <span id="delete-log-title"
                            class="font-semibold text-gray-800 dark:text-neutral-200"></span>?
                        <br>Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="mt-6 flex justify-center gap-x-4">
                        <button type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                            data-hs-overlay="#delete-modal">
                            Batal
                        </button>
                        <form id="delete-form" method="POST" class="inline" navigate-form>
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.setDeleteData = function(id, title) {
            document.getElementById('delete-log-title').textContent = title;
            const baseUrl = "{{ route('admin.log_book.delete', ['id' => ':id']) }}";
            document.getElementById('delete-form').action = baseUrl.replace(':id', id);
        };
    </script>
@endsection
