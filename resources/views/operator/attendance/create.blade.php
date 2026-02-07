@extends('layouts.user')

@section('title', 'Tambah Absensi')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-2xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 bg-sky-500 rounded-lg flex items-center justify-center">
                    <i data-lucide="plus" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Input Absensi Manual</h1>
                    <p class="text-sm text-gray-600">Tambahkan absensi untuk karyawan yang lupa check-in</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('operator.attendance.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-6">
                @csrf

                <div class="space-y-4">
                    {{-- Schedule --}}
                    <div>
                        <label for="schedule_id" class="block text-sm font-medium text-gray-700 mb-2">Karyawan & Shift</label>
                        <select name="schedule_id" id="schedule_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 @error('schedule_id') border-red-500 @enderror" required>
                            <option value="">Pilih Karyawan & Shift</option>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                    {{ $schedule->user->name }} - {{ $schedule->shift->shift_name }} ({{ $schedule->schedule_date->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('schedule_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 @error('status') border-red-500 @enderror" required>
                            <option value="">Pilih Status</option>
                            <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="telat" {{ old('status') == 'telat' ? 'selected' : '' }}>Telat</option>
                            <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="alpha" {{ old('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                        @error('status')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Check-in Time --}}
                    <div>
                        <label for="check_in_time" class="block text-sm font-medium text-gray-700 mb-2">Jam Check-in</label>
                        <input type="time" name="check_in_time" id="check_in_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 @error('check_in_time') border-red-500 @enderror" value="{{ old('check_in_time') }}" required>
                        @error('check_in_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Check-out Time --}}
                    <div>
                        <label for="check_out_time" class="block text-sm font-medium text-gray-700 mb-2">Jam Check-out (Opsional)</label>
                        <input type="time" name="check_out_time" id="check_out_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 @error('check_out_time') border-red-500 @enderror" value="{{ old('check_out_time') }}">
                        @error('check_out_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500" placeholder="Catatan mengenai absensi ini (opsional)">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 font-medium">
                        <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                        Simpan
                    </button>
                    <a href="{{ route('operator.attendance.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                        <i data-lucide="x" class="w-4 h-4 inline mr-2"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
