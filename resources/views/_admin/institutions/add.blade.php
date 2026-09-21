@extends('_admin._layout.app')

@section('title', 'Tambah Institusi Baru')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white overflow-hidden shadow-lg rounded-2xl dark:bg-neutral-800 border-2 border-gray-100 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center">
                <a href="{{ route('admin.institutions.index') }}"
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
                        Tambah Institusi Baru
                    </h2>
                </div>
            </div>

            <form id="add-form" class="p-6" navigate-form action="{{ route('admin.institutions.create') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    {{-- Nama Institusi --}}
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Nama Institusi <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: SMK Negeri 1 Surabaya / Universitas Brawijaya" required>
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tipe --}}
                    <div>
                        <label for="type" class="block text-sm font-medium mb-2 dark:text-white">Tipe Institusi <span
                                class="text-red-500">*</span></label>
                        <select id="type" name="type"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            <option value="SMK" {{ old('type') == 'SMK' ? 'selected' : '' }}>SMK</option>
                            <option value="Universitas" {{ old('type') == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                            <option value="Politeknik" {{ old('type') == 'Politeknik' ? 'selected' : '' }}>Politeknik</option>
                            <option value="SMA" {{ old('type') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="Lainnya" {{ old('type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('type')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2 dark:text-white">Email Resmi</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 @error('email') border-red-500 @enderror"
                            placeholder="Contoh: info@smkn1surabaya.sch.id">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Alamat --}}
                    <div>
                        <label for="address" class="block text-sm font-medium mb-2 dark:text-white">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="3"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500"
                            placeholder="Alamat domisili sekolah atau kampus...">{{ old('address') }}</textarea>
                    </div>

                    <div class="pt-4 flex justify-end gap-x-2">
                        <x-admin.button href="{{ route('admin.institutions.index') }}" color="secondary">
                            Batal
                        </x-admin.button>
                        <x-admin.button type="submit" color="primary" class="font-bold">
                            Simpan Institusi
                        </x-admin.button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
