@extends('_admin._layout.app')

@section('title', 'Data Institusi')

@section('content')
    <x-admin.page-header :title="'Data ' . $page['title']" subtitle="Kelola Data Sekolah / Universitas Mitra Magang">
        <x-admin.button href="{{ route('admin.institutions.add') }}" class="font-bold">
            @include('_admin._layout.icons.add')
            Tambah Institusi
        </x-admin.button>
    </x-admin.page-header>

    <div class="mb-6">
        <form action="{{ route('admin.institutions.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row items-center gap-3">
            <div class="w-full sm:w-72">
                <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Cari nama, email, atau telepon..." size="sm" />
            </div>
            <div class="w-full sm:w-48">
                @php
                    $typeOptions = [
                        'all' => 'Semua Tipe',
                        'SMK' => 'SMK',
                        'Universitas' => 'Universitas',
                        'Politeknik' => 'Politeknik',
                        'SMA' => 'SMA',
                        'Lainnya' => 'Lainnya',
                    ];
                @endphp
                <x-admin.select :label="null" name="type" :options="$typeOptions" :value="$type ?? 'all'" size="sm"
                    class="cursor-pointer" />
            </div>
            <div class="flex items-center gap-2">
                <x-admin.button type="submit" size="sm" color="primary">
                    @include('_admin._layout.icons.search')
                    Cari
                </x-admin.button>
                @if (!empty($keywords) || ($type ?? 'all') !== 'all')
                    <x-admin.button href="{{ route('admin.institutions.index') }}" size="sm" color="outline-secondary">
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
                    <x-admin.table.th>Institusi</x-admin.table.th>
                    <x-admin.table.th class="w-px whitespace-nowrap">Tipe</x-admin.table.th>
                    <x-admin.table.th class="w-px whitespace-nowrap">Kontak</x-admin.table.th>
                    <x-admin.table.th align="center" class="w-px whitespace-nowrap">Pembimbing</x-admin.table.th>
                    <x-admin.table.th align="center" class="w-px whitespace-nowrap">Siswa</x-admin.table.th>
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
                                    {{ strtoupper(substr($d->name, 0, 1)) }}
                                </span>
                                <div class="grow">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                        {{ $d->name }}
                                    </span>
                                    @if(!empty($d->address))
                                        <span class="block text-xs text-gray-500 dark:text-neutral-500 line-clamp-1">
                                            {{ $d->address }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-200">
                                {{ $d->type ?? 'SMK' }}
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span class="block text-sm text-gray-800 dark:text-neutral-200">
                                {{ $d->email ?: '-' }}
                            </span>
                            <span class="block text-xs text-gray-500 dark:text-neutral-400">
                                {{ $d->phone ?: '-' }}
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td align="center">
                            <span class="inline-flex items-center gap-x-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                {{ $d->mentors_count ?? 0 }} Pembimbing
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td align="center">
                            <span class="inline-flex items-center gap-x-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                {{ $d->interns_count ?? 0 }} Siswa
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td innerClass="px-6 py-1.5 flex items-center justify-end gap-x-1">
                            <a navigate
                                class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-100 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700"
                                href="{{ route('admin.institutions.detail', $d->id) }}" title="Lihat Detail & Pembimbing">
                                @include('_admin._layout.icons.view_detail')
                            </a>
                            <a navigate
                                class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:border-blue-300 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-500"
                                href="{{ route('admin.institutions.update', $d->id) }}" title="Edit Institusi">
                                @include('_admin._layout.icons.pencil')
                            </a>
                            <button type="button"
                                class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 hover:border-red-300 dark:border-red-800 dark:bg-red-900/20 dark:text-red-500 cursor-pointer"
                                title="Hapus" data-hs-overlay="#delete-modal"
                                onclick='setDeleteData({{ $d->id }}, @json($d->name))'>
                                @include('_admin._layout.icons.trash')
                            </button>
                        </x-admin.table.td>
                    </x-admin.table.tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-sm text-gray-500 dark:text-neutral-400">
                            Belum ada data institusi yang terdaftar.
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
    <div id="delete-modal"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1">
        <div
            class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div
                class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white">
                        Konfirmasi Hapus
                    </h3>
                    <button type="button"
                        class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600"
                        aria-label="Close" data-hs-overlay="#delete-modal">
                        <span class="sr-only">Close</span>
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <p class="text-gray-800 dark:text-neutral-400">
                        Apakah Anda yakin ingin menghapus institusi <b id="delete-institution-name" class="dark:text-white"></b>?
                    </p>
                </div>
                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                    <button type="button"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 cursor-pointer"
                        data-hs-overlay="#delete-modal">
                        Batal
                    </button>
                    <form id="delete-form" action="" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 disabled:opacity-50 disabled:pointer-events-none cursor-pointer">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setDeleteData(id, name) {
            document.getElementById('delete-institution-name').innerText = name;
            document.getElementById('delete-form').action = `{{ url('admin/institutions/delete') }}/${id}`;
        }
    </script>
@endsection
