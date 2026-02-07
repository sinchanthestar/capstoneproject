@extends('layouts.user')

@section('title', 'Monitoring Kehadiran Realtime')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        {{-- Header --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                <i data-lucide="activity" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Monitoring Realtime</h1>
                <p class="text-sm text-gray-600">Pantau status check-in karyawan hari ini</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('operator.monitoring.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ $date }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                    <select name="shift" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                        <option value="">Semua Shift</option>
                        @foreach($allShifts as $shift)
                            <option value="{{ $shift->id }}" {{ $shiftFilter == $shift->id ? 'selected' : '' }}>
                                {{ $shift->shift_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 items-end">
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-sm text-gray-600 mb-1">Total Jadwal</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-sm text-gray-600 mb-1">Sudah Check-in</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['checked_in'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-sm text-gray-600 mb-1">Belum Check-in</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['not_checked_in'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-sm text-gray-600 mb-1">Persentase</p>
                <p class="text-3xl font-bold text-sky-600">{{ $stats['percentage'] }}%</p>
            </div>
        </div>

        {{-- Two Column Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Sudah Check-in --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="bg-green-50 border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">✓ Sudah Check-in ({{ $checkedIn->count() }})</h2>
                </div>
                @if($checkedIn->count() > 0)
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        @foreach($checkedIn as $schedule)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $schedule->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $schedule->shift->shift_name }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i data-lucide="check" class="w-3 h-3 mr-1"></i> On Time
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Check-in: {{ $schedule->attendance->check_in_time->format('H:i') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                        <p>Tidak ada</p>
                    </div>
                @endif
            </div>

            {{-- Belum Check-in --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="bg-red-50 border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">✗ Belum Check-in ({{ $notCheckedIn->count() }})</h2>
                </div>
                @if($notCheckedIn->count() > 0)
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        @foreach($notCheckedIn as $schedule)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $schedule->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $schedule->shift->shift_name }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i data-lucide="x" class="w-3 h-3 mr-1"></i> Missing
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Shift: {{ $schedule->shift->start_time }} - {{ $schedule->shift->end_time }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        <i data-lucide="smile" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                        <p>Semua sudah check-in!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
