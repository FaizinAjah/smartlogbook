@extends('_admin._layout.app')

@section('title', 'Detail Peserta Magang: ' . $data->student_name)

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-x-3">
            <a href="{{ route('admin.interns.index') }}"
                class="py-3 px-3 inline-flex items-center gap-x-2 text-xl rounded-xl border border-gray-200 bg-white text-gray-800 shadow-md hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 cursor-pointer">
                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="90" height="90"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    {{ $data->student_name }}
                </h1>
                <p class="text-xs text-gray-500 dark:text-neutral-400">
                    {{ $data->institution_name }} &bull; {{ $data->major ?: 'Peserta Magang' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <x-admin.button href="{{ route('admin.log_book.index', ['user_id' => $data->user_id]) }}" color="primary" size="sm">
                @include('_admin._layout.icons.sidebar.log_book')
                Lihat Log Book Lengkap
            </x-admin.button>
            @if(Auth::user()?->access_type == App\Constants\UserConst::SUPERADMIN)
                <x-admin.button href="{{ route('admin.interns.update', $data->id) }}" color="secondary" size="sm">
                    @include('_admin._layout.icons.pencil')
                    Edit Peserta
                </x-admin.button>
            @endif
        </div>
    </div>

    {{-- Main Profile Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Student Profile Card --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center gap-x-4 mb-6">
                <span class="inline-flex items-center justify-center size-16 rounded-2xl bg-blue-100 text-blue-600 font-bold text-2xl dark:bg-blue-900/40 dark:text-blue-400">
                    {{ strtoupper(substr($data->student_name, 0, 1)) }}
                </span>
                <div>
                    <h2 class="font-bold text-gray-800 text-lg dark:text-white">{{ $data->student_name }}</h2>
                    <span class="text-xs text-gray-500 dark:text-neutral-400 block">{{ $data->student_email }}</span>
                    @if($data->status === 'aktif')
                        <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                            Aktif
                        </span>
                    @elseif($data->status === 'selesai')
                        <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                            Selesai
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">
                            {{ ucfirst($data->status) }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="space-y-3">
                <div class="p-3 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                    <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-1">NISN / NIM</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $data->nis_nim ?: '-' }}</p>
                </div>

                <div class="p-3 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                    <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-1">Jurusan / Program Studi</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $data->major ?: '-' }}</p>
                </div>

                <div class="p-3 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                    <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-1">Periode Magang</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-neutral-200">
                        {{ $data->start_date ? \Carbon\Carbon::parse($data->start_date)->translatedFormat('d M Y') : '-' }}
                        s/d
                        {{ $data->end_date ? \Carbon\Carbon::parse($data->end_date)->translatedFormat('d M Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Institution & Supervisor Info --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100 dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-x-2">
                        @include('_admin._layout.icons.sidebar.institution')
                        Institusi Asal
                    </h3>
                    <a href="{{ route('admin.institutions.detail', $data->institution_id) }}" class="text-xs font-semibold text-blue-600 hover:underline dark:text-blue-400">
                        Lihat Institusi &rarr;
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                        <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-1">Nama Sekolah / Kampus</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $data->institution_name }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                        <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-1">Program Studi / Bidang</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $data->major ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100 dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-x-2">
                        @include('_admin._layout.icons.sidebar.user')
                        Guru / Dosen Pembimbing
                    </h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-3 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                        <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-1">Nama Pembimbing</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $data->mentor_name }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                        <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-1">NIP / Identitas</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $data->mentor_identity_number ?: '-' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                        <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-1">No. Telepon / WhatsApp</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $data->mentor_phone ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Log Books Table --}}
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-bold text-gray-800 text-lg dark:text-white">
                    Aktivitas Log Book Terbaru
                </h3>
                <p class="text-xs text-gray-500 dark:text-neutral-400">
                    5 catatan aktivitas harian terakhir yang dikirimkan oleh siswa ini.
                </p>
            </div>
            <div>
                <x-admin.button href="{{ route('admin.log_book.index', ['user_id' => $data->user_id]) }}" color="secondary" size="sm">
                    Lihat Semua Logbook &rarr;
                </x-admin.button>
            </div>
        </div>

        <x-admin.table.wrapper>
            <x-admin.table>
                <x-admin.table.thead>
                    <tr>
                        <x-admin.table.th>Tanggal</x-admin.table.th>
                        <x-admin.table.th>Judul Aktivitas</x-admin.table.th>
                        <x-admin.table.th align="center">Kehadiran</x-admin.table.th>
                        <x-admin.table.th align="center">Status Review</x-admin.table.th>
                        <x-admin.table.th align="end" class="w-px whitespace-nowrap">Detail</x-admin.table.th>
                    </tr>
                </x-admin.table.thead>
                <x-admin.table.tbody>
                    @forelse($recentLogs as $log)
                        <x-admin.table.tr>
                            <x-admin.table.td class="whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                    {{ \Carbon\Carbon::parse($log->log_date)->translatedFormat('l, d M Y') }}
                                </span>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    {{ $log->title }}
                                </span>
                            </x-admin.table.td>
                            <x-admin.table.td align="center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300 capitalize">
                                    {{ $log->attendance_status ?? 'Masuk' }}
                                </span>
                            </x-admin.table.td>
                            <x-admin.table.td align="center">
                                @if($log->status === 'sudah_direview' || $log->status === 'Sudah Direview')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        Sudah Direview
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                        Menunggu Review
                                    </span>
                                @endif
                            </x-admin.table.td>
                            <x-admin.table.td innerClass="px-6 py-1.5 flex items-center justify-end">
                                <a navigate href="{{ route('admin.log_book.detail', $log->id) }}"
                                    class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700"
                                    title="Lihat Detail Log">
                                    @include('_admin._layout.icons.view_detail')
                                </a>
                            </x-admin.table.td>
                        </x-admin.table.tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-sm text-gray-500 dark:text-neutral-400">
                                <x-admin.empty-state />
                            </td>
                        </tr>
                    @endforelse
                </x-admin.table.tbody>
            </x-admin.table>
        </x-admin.table.wrapper>
    </div>
@endsection
