@extends('_admin._layout.app')

@section('title', 'Edit Institusi')

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
                        Edit Data Institusi
                    </h2>
                </div>
            </div>

            <form id="update-form" class="p-6" navigate-form action="{{ route('admin.institutions.doUpdate', $institutionId) }}" method="POST">
                @csrf

                <div class="space-y-4">
                    {{-- Nama Institusi --}}
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Nama Institusi <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $data->name) }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 @error('name') border-red-500 @enderror"
                            required>
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
                            @foreach(['SMK', 'Universitas', 'Politeknik', 'SMA', 'Lainnya'] as $t)
                                <option value="{{ $t }}" {{ old('type', $data->type ?? 'SMK') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2 dark:text-white">Email Resmi</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $data->email) }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium mb-2 dark:text-white">Nomor Telepon / Kontak</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $data->phone) }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Website --}}
                    <div>
                        <label for="website" class="block text-sm font-medium mb-2 dark:text-white">Website</label>
                        <input type="text" id="website" name="website" value="{{ old('website', $data->website) }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 @error('website') border-red-500 @enderror">
                        @error('website')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="address" class="block text-sm font-medium mb-2 dark:text-white">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="3"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500">{{ old('address', $data->address) }}</textarea>
                    </div>

                    <div class="pt-4 flex justify-end gap-x-2">
                        <x-admin.button href="{{ route('admin.institutions.index') }}" color="secondary">
                            Batal
                        </x-admin.button>
                        <x-admin.button type="submit" color="primary" class="font-bold">
                            Perbarui Institusi
                        </x-admin.button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
