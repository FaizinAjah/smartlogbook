@extends('_admin._layout.app')

@section('title', 'Tambah Peserta Magang')

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
                        Tambah Peserta Magang
                    </h2>
                </div>
            </div>

            <form id="add-form" class="p-6" navigate-form action="{{ route('admin.interns.create') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    {{-- Student Source Option --}}
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white">Pilih Akun Siswa <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-neutral-900 border-gray-200 dark:border-neutral-700 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 dark:has-[:checked]:bg-blue-900/20">
                                <input type="radio" name="student_source" value="existing" class="text-blue-600 focus:ring-blue-500" {{ old('student_source', 'existing') === 'existing' ? 'checked' : '' }} onchange="toggleStudentSource()">
                                <span class="ms-2 text-xs font-semibold text-gray-800 dark:text-white">Pilih dari Akun Terdaftar</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-neutral-900 border-gray-200 dark:border-neutral-700 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 dark:has-[:checked]:bg-blue-900/20">
                                <input type="radio" name="student_source" value="new" class="text-blue-600 focus:ring-blue-500" {{ old('student_source') === 'new' ? 'checked' : '' }} onchange="toggleStudentSource()">
                                <span class="ms-2 text-xs font-semibold text-gray-800 dark:text-white">Buat Akun Siswa Baru</span>
                            </label>
                        </div>
                    </div>

                    {{-- Existing User Select --}}
                    <div id="existing-user-wrapper">
                        <label for="user_id" class="block text-sm font-medium mb-2 dark:text-white">Pilih Akun Pengguna Siswa <span class="text-red-500">*</span></label>
                        <select id="user_id" name="user_id"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($students as $s)
                                <option value="{{ $s->id }}" {{ old('user_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New User Inputs --}}
                    <div id="new-user-wrapper" class="space-y-4 hidden">
                        <div>
                            <label for="new_student_name" class="block text-sm font-medium mb-1 dark:text-white">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                            <input type="text" id="new_student_name" name="new_student_name" value="{{ old('new_student_name') }}"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 placeholder-neutral-300 dark:placeholder-neutral-500"
                                placeholder="Contoh: Muhammad Kevin">
                            @error('new_student_name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="new_student_email" class="block text-sm font-medium mb-1 dark:text-white">Email Akun Login Siswa <span class="text-red-500">*</span></label>
                            <input type="email" id="new_student_email" name="new_student_email" value="{{ old('new_student_email') }}"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 placeholder-neutral-300 dark:placeholder-neutral-500"
                                placeholder="Contoh: kevin@gmail.com">
                            @error('new_student_email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Institusi --}}
                    <div>
                        <label for="institution_id" class="block text-sm font-medium mb-2 dark:text-white">Asal Institusi (Sekolah/Kampus) <span class="text-red-500">*</span></label>
                        <select id="institution_id" name="institution_id" onchange="onInstitutionChange(this.value)"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 cursor-pointer" required>
                            <option value="">-- Pilih Institusi --</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ old('institution_id') == $inst->id ? 'selected' : '' }}>
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
                            <option value="">-- Pilih Pembimbing --</option>
                            @foreach($mentors as $m)
                                <option value="{{ $m->id }}" data-institution="{{ $m->institution_id }}" {{ old('mentor_id') == $m->id ? 'selected' : '' }}>
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
                            <input type="text" id="nis_nim" name="nis_nim" value="{{ old('nis_nim') }}"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 placeholder-neutral-300 dark:placeholder-neutral-500"
                                placeholder="Nomor induk siswa/mahasiswa">
                        </div>

                        {{-- Jurusan --}}
                        <div>
                            <label for="major" class="block text-sm font-medium mb-2 dark:text-white">Jurusan / Program Studi</label>
                            <input type="text" id="major" name="major" value="{{ old('major') }}"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 placeholder-neutral-300 dark:placeholder-neutral-500"
                                placeholder="Contoh: Rekayasa Perangkat Lunak / Teknik Informatika">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Tanggal Mulai --}}
                        <div>
                            <x-admin.input type="text" id="start_date" name="start_date" label="Tanggal Mulai Magang" class="datepicker"
                                placeholder="Pilih tanggal mulai" autocomplete="off" value="{{ old('start_date') }}"
                                error="{{ $errors->first('start_date') }}" />
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div>
                            <x-admin.input type="text" id="end_date" name="end_date" label="Tanggal Selesai Magang" class="datepicker"
                                placeholder="Pilih tanggal selesai" autocomplete="off" value="{{ old('end_date') }}"
                                error="{{ $errors->first('end_date') }}" />
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium mb-2 dark:text-white">Status Magang</label>
                        <select id="status" name="status"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300">
                            <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="keluar" {{ old('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end gap-x-2">
                        <x-admin.button href="{{ route('admin.interns.index') }}" color="secondary">
                            Batal
                        </x-admin.button>
                        <x-admin.button type="submit" color="primary" class="font-bold">
                            Simpan Data Peserta
                        </x-admin.button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleStudentSource() {
            const isNew = document.querySelector('input[name="student_source"]:checked')?.value === 'new';
            const existingWrapper = document.getElementById('existing-user-wrapper');
            const newWrapper = document.getElementById('new-user-wrapper');

            if (isNew) {
                existingWrapper.classList.add('hidden');
                newWrapper.classList.remove('hidden');
            } else {
                existingWrapper.classList.remove('hidden');
                newWrapper.classList.add('hidden');
            }
        }

        async function onInstitutionChange(institutionId) {
            const mentorSelect = document.getElementById('mentor_id');
            if (!institutionId) {
                return;
            }

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

        // Initialize view state
        toggleStudentSource();

        if (window.flatpickr) {
            window.flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                allowInput: true
            });
        }
    </script>
@endsection
