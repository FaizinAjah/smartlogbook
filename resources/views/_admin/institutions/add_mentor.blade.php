@extends('_admin._layout.app')

@section('title', 'Tambah Pembimbing - ' . $institution->name)

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white overflow-hidden shadow-lg rounded-2xl dark:bg-neutral-800 border-2 border-gray-100 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center">
                <a href="{{ route('admin.institutions.detail', $institution->id) }}"
                    class="py-3 px-3 inline-flex items-center gap-x-2 text-xl rounded-xl border border-gray-200 bg-white text-gray-800 shadow-md hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 cursor-pointer">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="90" height="90"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div class="ms-3">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        Tambah Pembimbing Baru
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-neutral-400">
                        Institusi: <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $institution->name }}</span>
                    </p>
                </div>
            </div>

            <form id="add-mentor-form" class="p-6" navigate-form action="{{ route('admin.institutions.mentors.create', $institution->id) }}" method="POST">
                @csrf

                <div class="space-y-4">
                    {{-- Nama Pembimbing --}}
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2 dark:text-white">
                            Nama Lengkap Pembimbing <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Drs. Budi Santoso, M.Kom" required>
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email Akun Login --}}
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2 dark:text-white">
                            Email (Akun Login Aplikasi) <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 @error('email') border-red-500 @enderror"
                            placeholder="Contoh: budi@smkn1.sch.id" required>
                        <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                            Akun login dengan hak akses Pembimbing akan otomatis dibuat jika belum terdaftar.
                        </p>
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIP / NIDN / No. Identitas --}}
                    <div>
                        <label for="identity_number" class="block text-sm font-medium mb-2 dark:text-white">
                            NIP / NIDN / No. Identitas
                        </label>
                        <input type="text" id="identity_number" name="identity_number" value="{{ old('identity_number') }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 @error('identity_number') border-red-500 @enderror"
                            placeholder="Contoh: 19800215 200501 1 002">
                        @error('identity_number')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Telepon / WhatsApp --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium mb-2 dark:text-white">
                            Nomor Telepon / WhatsApp
                        </label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 @error('phone') border-red-500 @enderror"
                            placeholder="Contoh: 08123456789">
                        @error('phone')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex justify-end gap-x-2">
                        <x-admin.button href="{{ route('admin.institutions.detail', $institution->id) }}" color="secondary">
                            Batal
                        </x-admin.button>
                        <x-admin.button type="submit" color="primary" class="font-bold">
                            Simpan Pembimbing
                        </x-admin.button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
