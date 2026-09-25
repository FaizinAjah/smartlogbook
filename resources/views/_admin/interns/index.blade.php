@extends('_admin._layout.app')

@section('title', 'Data Peserta Magang')

@section('content')
    <x-admin.page-header :title="'Data ' . $page['title']" subtitle="Kelola Data Siswa/Mahasiswa yang Sedang Melaksanakan Magang">
        @if($isSuperadmin)
            <x-admin.button href="{{ route('admin.interns.add') }}" class="font-bold">
                @include('_admin._layout.icons.add')
                Tambah Peserta Magang
            </x-admin.button>
        @endif
    </x-admin.page-header>

    <div class="mb-6">
        <form action="{{ route('admin.interns.index') }}" method="GET" navigate-form
            class="flex flex-wrap items-center gap-3">
            <div class="w-full sm:w-64">
                <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Cari nama, NISN, jurusan..." size="sm" />
            </div>

            @if($isSuperadmin)
                <div class="w-full sm:w-52">
                    <select name="institution_id" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 cursor-pointer">
                        <option value="">Semua Institusi</option>
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->id }}" {{ ($institution_id ?? '') == $inst->id ? 'selected' : '' }}>
                                {{ $inst->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-52">
                    <select name="mentor_id" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 cursor-pointer">
                        <option value="">Semua Pembimbing</option>
                        @foreach($mentors as $m)
                            <option value="{{ $m->id }}" {{ ($mentor_id ?? '') == $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="w-full sm:w-40">
                <select name="status" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 cursor-pointer">
                    <option value="all">Semua Status</option>
                    <option value="aktif" {{ ($status ?? 'all') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ ($status ?? '') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="keluar" {{ ($status ?? '') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <x-admin.button type="submit" size="sm" color="primary">
                    @include('_admin._layout.icons.search')
                    Cari
                </x-admin.button>
                @if (!empty($keywords) || !empty($institution_id) || !empty($mentor_id) || ($status ?? 'all') !== 'all')
                    <x-admin.button href="{{ route('admin.interns.index') }}" size="sm" color="outline-secondary">
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
                    <x-admin.table.th>Peserta Magang</x-admin.table.th>
                    <x-admin.table.th class="w-px whitespace-nowrap">Institusi & Jurusan</x-admin.table.th>
                    <x-admin.table.th class="w-px whitespace-nowrap">Pembimbing</x-admin.table.th>
                    <x-admin.table.th align="center" class="w-px whitespace-nowrap">Status</x-admin.table.th>
                    <x-admin.table.th align="end" class="w-px whitespace-nowrap"></x-admin.table.th>
                </tr>
            </x-admin.table.thead>
            <x-admin.table.tbody>
                @forelse($data as $d)
                    <x-admin.table.tr>
                        <x-admin.table.td class="!whitespace-normal min-w-[14rem]">
                            <div class="flex items-center gap-x-3">
                                <span
                                    class="inline-flex items-center justify-center size-9.5 rounded-full bg-blue-50 border border-blue-200 text-blue-600 font-bold dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-400">
                                    {{ strtoupper(substr($d->student_name, 0, 1)) }}
                                </span>
                                <div class="grow">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                        {{ $d->student_name }}
                                    </span>
                                    <span class="block text-xs text-gray-500 dark:text-neutral-500">
                                        {{ $d->student_email }}
                                    </span>
                                    @if($d->nis_nim)
                                        <span class="inline-block mt-0.5 text-[11px] text-gray-400 dark:text-neutral-400">
                                            NISN/NIM: {{ $d->nis_nim }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $d->institution_name }}
                            </span>
                            <span class="block text-xs text-gray-500 dark:text-neutral-400">
                                {{ $d->major ?: '-' }}
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span class="inline-flex items-center gap-x-1.5 text-sm font-medium text-gray-800 dark:text-neutral-200">
                                {{ $d->mentor_name }}
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td align="center">
                            @if($d->status === 'aktif')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    Aktif
                                </span>
                            @elseif($d->status === 'selesai')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">
                                    {{ ucfirst($d->status) }}
                                </span>
                            @endif
                        </x-admin.table.td>
                        <x-admin.table.td innerClass="px-6 py-1.5 flex items-center justify-end gap-x-1">
                            <a navigate
                                class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-100 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700"
                                href="{{ route('admin.interns.detail', $d->id) }}" title="Lihat Profil & Log">
                                @include('_admin._layout.icons.view_detail')
                            </a>
                            @if($isSuperadmin)
                                <a navigate
                                    class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:border-blue-300 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-500"
                                    href="{{ route('admin.interns.update', $d->id) }}" title="Edit Peserta">
                                    @include('_admin._layout.icons.pencil')
                                </a>
                                <button type="button"
                                    class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 hover:border-red-300 dark:border-red-800 dark:bg-red-900/20 dark:text-red-500 cursor-pointer"
                                    title="Hapus" data-hs-overlay="#delete-modal"
                                    onclick='setDeleteData({{ $d->id }}, @json($d->student_name))'>
                                    @include('_admin._layout.icons.trash')
                                </button>
                            @endif
                        </x-admin.table.td>
                    </x-admin.table.tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-sm text-gray-500 dark:text-neutral-400">
                            Belum ada data peserta magang yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </x-admin.table.tbody>
        </x-admin.table>
    </x-admin.table.wrapper>

    @if (method_exists($data, 'links'))
        <div class="mt-4">
            {{ $data->links() }}
        </div>
    @endif

    {{-- Delete Modal --}}
    @if($isSuperadmin)
        <div id="delete-modal"
            class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
            role="dialog" tabindex="-1">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
                <div
                    class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                        <h3 class="font-bold text-gray-800 dark:text-white">
                            Konfirmasi Hapus
                        </h3>
                        <button type="button"
                            class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-neutral-700 dark:text-neutral-400"
                            data-hs-overlay="#delete-modal">
                            ✕
                        </button>
                    </div>
                    <div class="p-4">
                        <p class="text-gray-800 dark:text-neutral-400">
                            Apakah Anda yakin ingin menghapus peserta magang <b id="delete-intern-name" class="dark:text-white"></b>?
                        </p>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                        <button type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white cursor-pointer"
                            data-hs-overlay="#delete-modal">
                            Batal
                        </button>
                        <form id="delete-form" action="" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 cursor-pointer">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function setDeleteData(id, name) {
                document.getElementById('delete-intern-name').innerText = name;
                document.getElementById('delete-form').action = `{{ url('admin/peserta-magang/delete') }}/${id}`;
            }
        </script>
    @endif
@endsection
