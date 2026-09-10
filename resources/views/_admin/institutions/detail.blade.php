@extends('_admin._layout.app')

@section('title', 'Detail Institusi: ' . $institution->name)

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-x-3">
            <a href="{{ route('admin.institutions.index') }}"
                class="py-2.5 px-3 inline-flex items-center gap-x-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 cursor-pointer">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
                Kembali
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    {{ $institution->name }}
                </h1>
                <p class="text-xs text-gray-500 dark:text-neutral-400">
                    Detail Data Institusi & Pengelolaan Pembimbing
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <x-admin.button href="{{ route('admin.institutions.update', $institution->id) }}" color="secondary" size="sm">
                @include('_admin._layout.icons.pencil')
                Edit Data
            </x-admin.button>
            <button type="button" data-hs-overlay="#add-mentor-modal"
                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none cursor-pointer">
                @include('_admin._layout.icons.add')
                Tambah Pembimbing
            </button>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center gap-x-4 mb-4">
                <span class="inline-flex items-center justify-center size-14 rounded-2xl bg-blue-100 text-blue-600 font-bold text-2xl dark:bg-blue-900/40 dark:text-blue-400">
                    {{ strtoupper(substr($institution->name, 0, 1)) }}
                </span>
                <div>
                    <h2 class="font-bold text-gray-800 text-lg dark:text-white">{{ $institution->name }}</h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                        {{ $institution->type ?? 'SMK' }}
                    </span>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-xs text-gray-500 dark:text-neutral-400 uppercase font-semibold block">Email</span>
                    <span class="text-gray-800 dark:text-neutral-200">{{ $institution->email ?: '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-neutral-400 uppercase font-semibold block">Telepon</span>
                    <span class="text-gray-800 dark:text-neutral-200">{{ $institution->phone ?: '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-neutral-400 uppercase font-semibold block">Website</span>
                    @if($institution->website)
                        <a href="{{ $institution->website }}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">{{ $institution->website }}</a>
                    @else
                        <span class="text-gray-800 dark:text-neutral-200">-</span>
                    @endif
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-neutral-400 uppercase font-semibold block">Alamat</span>
                    <span class="text-gray-800 dark:text-neutral-200">{{ $institution->address ?: '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Statistics & Overview --}}
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-500 dark:text-neutral-400">Total Pembimbing</span>
                    <span class="size-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center dark:bg-blue-900/30 dark:text-blue-400">
                        @include('_admin._layout.icons.sidebar.user')
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-gray-800 dark:text-white">
                        {{ count($mentors) }}
                    </span>
                    <span class="text-xs text-gray-500 block mt-1 dark:text-neutral-400">
                        Guru / Dosen pembimbing institusi terdaftar
                    </span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-500 dark:text-neutral-400">Total Siswa Binaan</span>
                    <span class="size-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center dark:bg-emerald-900/30 dark:text-emerald-400">
                        @include('_admin._layout.icons.sidebar.intern')
                    </span>
                </div>
                <div class="mt-4">
                    @php
                        $totalInterns = 0;
                        foreach($mentors as $m) { $totalInterns += $m->interns_count; }
                    @endphp
                    <span class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">
                        {{ $totalInterns }}
                    </span>
                    <span class="text-xs text-gray-500 block mt-1 dark:text-neutral-400">
                        Peserta magang aktif dari institusi ini
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Sub-panel CRUD Pembimbing --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800 text-lg dark:text-white">
                    Data Guru / Dosen Pembimbing
                </h3>
                <p class="text-xs text-gray-500 dark:text-neutral-400">
                    Pembimbing yang berwenang memantau siswa magang dari institusi ini.
                </p>
            </div>
            <button type="button" data-hs-overlay="#add-mentor-modal"
                class="py-2 px-3 inline-flex items-center gap-x-1.5 text-xs font-semibold rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 cursor-pointer">
                @include('_admin._layout.icons.add')
                Tambah Pembimbing
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-900/50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">Nama Pembimbing</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">NIP / Identitas</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">Jabatan</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">Akun Login</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">Siswa Binaan</th>
                        <th class="px-6 py-3 text-end text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($mentors as $m)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/60">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    <span class="inline-flex items-center justify-center size-8 rounded-full bg-blue-100 text-blue-600 font-semibold text-xs dark:bg-blue-900/40 dark:text-blue-300">
                                        {{ strtoupper(substr($m->name, 0, 1)) }}
                                    </span>
                                    <div>
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-white">{{ $m->name }}</span>
                                        <span class="block text-xs text-gray-500 dark:text-neutral-400">{{ $m->phone ?: '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                {{ $m->identity_number ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                {{ $m->position ?: 'Pembimbing' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $m->user_email ?? '-' }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                    Role: Pembimbing
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                    {{ $m->interns_count }} Siswa
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-end">
                                <div class="flex items-center justify-end gap-x-1">
                                    <button type="button"
                                        class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-500 cursor-pointer"
                                        title="Edit Pembimbing" data-hs-overlay="#edit-mentor-modal"
                                        onclick='setEditMentorData(@json($m))'>
                                        @include('_admin._layout.icons.pencil')
                                    </button>
                                    <button type="button"
                                        class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-500 cursor-pointer"
                                        title="Hapus Pembimbing" data-hs-overlay="#delete-mentor-modal"
                                        onclick='setDeleteMentorData({{ $m->id }}, @json($m->name))'>
                                        @include('_admin._layout.icons.trash')
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-sm text-gray-500 dark:text-neutral-400">
                                Belum ada pembimbing yang ditambahkan untuk institusi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Pembimbing --}}
    <div id="add-mentor-modal"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border shadow-sm rounded-2xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white">
                        Tambah Pembimbing Baru
                    </h3>
                    <button type="button"
                        class="size-8 inline-flex justify-center items-center rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-neutral-700 dark:text-neutral-400 cursor-pointer"
                        data-hs-overlay="#add-mentor-modal">
                        ✕
                    </button>
                </div>
                <form action="{{ route('admin.institutions.mentors.create', $institution->id) }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-white">Nama Lengkap Pembimbing <span class="text-red-500">*</span></label>
                            <input type="text" name="name" class="py-2.5 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300"
                                placeholder="Contoh: Drs. Budi Santoso, M.Kom" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-white">Email (Akun Login Aplikasi) <span class="text-red-500">*</span></label>
                            <input type="email" name="email" class="py-2.5 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300"
                                placeholder="Contoh: budi@smkn1.sch.id" required>
                            <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                                Akun login dengan hak akses Pembimbing akan otomatis dibuat jika belum terdaftar.
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-white">NIP / NIDN / No. Identitas</label>
                            <input type="text" name="identity_number" class="py-2.5 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300"
                                placeholder="Contoh: 19800215 200501 1 002">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-white">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" class="py-2.5 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300"
                                placeholder="Contoh: 08123456789">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-white">Posisi / Jabatan</label>
                            <input type="text" name="position" class="py-2.5 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300"
                                value="Guru Pembimbing" placeholder="Contoh: Guru Pembimbing / Kaprog RPL">
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                        <button type="button" class="py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white cursor-pointer"
                            data-hs-overlay="#add-mentor-modal">
                            Batal
                        </button>
                        <button type="submit" class="py-2 px-3 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 cursor-pointer">
                            Simpan Pembimbing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Pembimbing --}}
    <div id="edit-mentor-modal"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border shadow-sm rounded-2xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white">
                        Edit Data Pembimbing
                    </h3>
                    <button type="button"
                        class="size-8 inline-flex justify-center items-center rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-neutral-700 dark:text-neutral-400 cursor-pointer"
                        data-hs-overlay="#edit-mentor-modal">
                        ✕
                    </button>
                </div>
                <form id="edit-mentor-form" action="" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-white">Nama Lengkap Pembimbing <span class="text-red-500">*</span></label>
                            <input type="text" id="edit-mentor-name" name="name" class="py-2.5 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-white">Email (Akun Login) <span class="text-red-500">*</span></label>
                            <input type="email" id="edit-mentor-email" name="email" class="py-2.5 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-white">NIP / NIDN / No. Identitas</label>
                            <input type="text" id="edit-mentor-nip" name="identity_number" class="py-2.5 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-white">Nomor Telepon</label>
                            <input type="text" id="edit-mentor-phone" name="phone" class="py-2.5 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-white">Posisi / Jabatan</label>
                            <input type="text" id="edit-mentor-position" name="position" class="py-2.5 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300">
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                        <button type="button" class="py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white cursor-pointer"
                            data-hs-overlay="#edit-mentor-modal">
                            Batal
                        </button>
                        <button type="submit" class="py-2 px-3 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 cursor-pointer">
                            Perbarui Pembimbing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Hapus Pembimbing --}}
    <div id="delete-mentor-modal"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border shadow-sm rounded-2xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white">
                        Hapus Pembimbing
                    </h3>
                    <button type="button"
                        class="size-8 inline-flex justify-center items-center rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-neutral-700 dark:text-neutral-400 cursor-pointer"
                        data-hs-overlay="#delete-mentor-modal">
                        ✕
                    </button>
                </div>
                <div class="p-4">
                    <p class="text-gray-800 dark:text-neutral-400">
                        Apakah Anda yakin ingin menghapus data pembimbing <b id="delete-mentor-name" class="dark:text-white"></b>?
                    </p>
                </div>
                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                    <button type="button" class="py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white cursor-pointer"
                        data-hs-overlay="#delete-mentor-modal">
                        Batal
                    </button>
                    <form id="delete-mentor-form" action="" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="py-2 px-3 text-sm font-semibold rounded-lg bg-red-600 text-white hover:bg-red-700 cursor-pointer">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setEditMentorData(mentor) {
            document.getElementById('edit-mentor-name').value = mentor.name || '';
            document.getElementById('edit-mentor-email').value = mentor.user_email || '';
            document.getElementById('edit-mentor-nip').value = mentor.identity_number || '';
            document.getElementById('edit-mentor-phone').value = mentor.phone || '';
            document.getElementById('edit-mentor-position').value = mentor.position || '';
            document.getElementById('edit-mentor-form').action = `{{ url('admin/institutions/mentors/update') }}/${mentor.id}`;
        }

        function setDeleteMentorData(id, name) {
            document.getElementById('delete-mentor-name').innerText = name;
            document.getElementById('delete-mentor-form').action = `{{ url('admin/institutions/mentors/delete') }}/${id}`;
        }
    </script>
@endsection
