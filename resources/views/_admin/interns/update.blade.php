@extends('_admin._layout.app')

@section('title', 'Edit Peserta Magang')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white overflow-hidden shadow-lg rounded-2xl dark:bg-neutral-800 border-2 border-gray-100 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center">
                <a href="{{ route('admin.interns.index') }}"
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
                        Edit Data Peserta Magang
                    </h2>
                </div>
            </div>

            <form id="update-form" class="p-6" navigate-form action="{{ route('admin.interns.doUpdate', $internId) }}" method="POST">
                @csrf

                <div class="space-y-4">
                    {{-- Student Name (Readonly) --}}
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-white">Nama Siswa</label>
                        <input type="text" value="{{ $data->student_name }} ({{ $data->student_email }})" disabled
                            class="py-3 px-4 block w-full bg-gray-100 border-gray-200 rounded-lg text-sm text-gray-600 dark:bg-neutral-900/60 dark:border-neutral-700 dark:text-neutral-400">
                    </div>

                    {{-- Institusi --}}
                    <div>
                        <label for="institution_id" class="block text-sm font-medium mb-2 dark:text-white">Asal Institusi <span class="text-red-500">*</span></label>
                        <select id="institution_id" name="institution_id" onchange="onInstitutionChange(this.value)"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 cursor-pointer" required>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ old('institution_id', $data->institution_id) == $inst->id ? 'selected' : '' }}>
                                    {{ $inst->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('institution_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pembimbing --}}
                    <div>
                        <label for="mentor_id" class="block text-sm font-medium mb-2 dark:text-white">Guru / Dosen Pembimbing <span class="text-red-500">*</span></label>
                        <select id="mentor_id" name="mentor_id"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 cursor-pointer" required>
                            @foreach($mentors as $m)
                                <option value="{{ $m->id }}" {{ old('mentor_id', $data->mentor_id) == $m->id ? 'selected' : '' }}>
                                    {{ $m->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mentor_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- NIS/NIM --}}
                        <div>
                            <label for="nis_nim" class="block text-sm font-medium mb-2 dark:text-white">NISN / NIM</label>
                            <input type="text" id="nis_nim" name="nis_nim" value="{{ old('nis_nim', $data->nis_nim) }}"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 placeholder-neutral-300 dark:placeholder-neutral-500">
                        </div>

                        {{-- Jurusan --}}
                        <div>
                            <label for="major" class="block text-sm font-medium mb-2 dark:text-white">Jurusan / Program Studi</label>
                            <input type="text" id="major" name="major" value="{{ old('major', $data->major) }}"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 placeholder-neutral-300 dark:placeholder-neutral-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Tanggal Mulai --}}
                        <div>
                            <label for="start_date" class="block text-sm font-medium mb-2 dark:text-white">Tanggal Mulai Magang</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $data->start_date) }}"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300">
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div>
                            <label for="end_date" class="block text-sm font-medium mb-2 dark:text-white">Tanggal Selesai Magang</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $data->end_date) }}"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium mb-2 dark:text-white">Status Magang <span class="text-red-500">*</span></label>
                        <select id="status" name="status"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300" required>
                            <option value="aktif" {{ old('status', $data->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ old('status', $data->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="keluar" {{ old('status', $data->status) == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                        @error('status')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label for="notes" class="block text-sm font-medium mb-2 dark:text-white">Catatan Tambahan</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 placeholder-neutral-300 dark:placeholder-neutral-500">{{ old('notes', $data->notes) }}</textarea>
                    </div>

                    <div class="pt-4 flex justify-end gap-x-2">
                        <x-admin.button href="{{ route('admin.interns.index') }}" color="secondary">
                            Batal
                        </x-admin.button>
                        <x-admin.button type="submit" color="primary" class="font-bold">
                            Perbarui Data Peserta
                        </x-admin.button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function onInstitutionChange(institutionId) {
            const mentorSelect = document.getElementById('mentor_id');
            if (!institutionId) return;

            try {
                const res = await fetch(`{{ url('admin/peserta-magang/mentors-by-institution') }}/${institutionId}`);
                if (res.ok) {
                    const mentors = await res.json();
                    mentorSelect.innerHTML = '<option value="">-- Pilih Pembimbing --</option>';
                    mentors.forEach(m => {
                        const opt = document.createElement('option');
                        opt.value = m.id;
                        opt.textContent = m.name;
                        mentorSelect.appendChild(opt);
                    });
                }
            } catch (err) {
                console.error(err);
            }
        }
    </script>
@endsection
