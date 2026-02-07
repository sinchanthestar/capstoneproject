@extends('layouts.user')

@section('title', 'Edit Absensi')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-2xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 bg-sky-500 rounded-lg flex items-center justify-center">
                    <i data-lucide="edit" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Absensi</h1>
                    <p class="text-sm text-gray-600">{{ $attendance->user->name }} - {{ is_string($attendance->schedule->schedule_date) ? $attendance->schedule->schedule_date : $attendance->schedule->schedule_date->format('d M Y') }}</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('operator.attendance.update', $attendance) }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    {{-- Check-in Time --}}
                    <div>
                        <label for="check_in_time" class="block text-sm font-medium text-gray-700 mb-2">Jam Check-in</label>
                        <input type="time" name="check_in_time" id="check_in_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 @error('check_in_time') border-red-500 @enderror" value="{{ old('check_in_time', is_string($attendance->check_in_time) ? substr($attendance->check_in_time, 0, 5) : $attendance->check_in_time?->format('H:i')) }}" required>
                        @error('check_in_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Check-out Time --}}
                    <div>
                        <label for="check_out_time" class="block text-sm font-medium text-gray-700 mb-2">Jam Check-out</label>
                        <input type="time" name="check_out_time" id="check_out_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 @error('check_out_time') border-red-500 @enderror" value="{{ old('check_out_time', is_string($attendance->check_out_time) ? substr($attendance->check_out_time, 0, 5) : $attendance->check_out_time?->format('H:i')) }}">
                        @error('check_out_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Is Late --}}
                    <div>
                        <input type="hidden" name="is_late" value="0">
                        <label for="is_late" class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_late" id="is_late" value="1" {{ old('is_late', $attendance->is_late) ? 'checked' : '' }} class="w-4 h-4 text-sky-600 rounded focus:ring-2 focus:ring-sky-500">
                            <span class="text-sm font-medium text-gray-700">Tandai sebagai Telat</span>
                        </label>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 @error('status') border-red-500 @enderror" required>
                            <option value="">Pilih Status</option>
                            <option value="hadir" {{ old('status', $attendance->status) === 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="telat" {{ old('status', $attendance->status) === 'telat' ? 'selected' : '' }}>Telat</option>
                            <option value="izin" {{ old('status', $attendance->status) === 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="alpha" {{ old('status', $attendance->status) === 'alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                        @error('status')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
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
