@extends('layouts.user')

@section('title', 'Kelola Absensi')

@section('content')<div class="min-h-screen bg-gray-50">
    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i data-lucide="clipboard-list" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Kelola Absensi Harian</h1>
                    <p class="text-sm text-gray-600">Manage attendance records</p>
                </div>
            </div>
            <a href="{{ route('operator.attendance.create') }}" class="inline-flex items-center gap-2 bg-sky-600 text-white px-4 py-2.5 rounded-lg hover:bg-sky-700 transition-colors font-medium text-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Input Manual
            </a>
        </div>

        {{-- Success Alert --}}
        @if($message = session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="font-medium text-green-900">{{ $message }}</p>
                </div>
            </div>
        @endif

        {{-- Filter Form --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <form method="GET" action="{{ route('operator.attendance.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ request('date', now()->toDateString()) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                    <select name="shift" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                        <option value="">Semua Shift</option>
                        @foreach($allShifts as $shift)
                            <option value="{{ $shift->id }}" {{ request('shift') == $shift->id ? 'selected' : '' }}>
                                {{ $shift->shift_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Karyawan</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama karyawan..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-sky-600 text-white px-3 py-2 rounded-lg hover:bg-sky-700 transition-colors font-medium text-sm">
                        Filter
                    </button>
                    <a href="{{ route('operator.attendance.index') }}" class="flex-1 bg-gray-200 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-300 transition-colors font-medium text-sm text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Two Column Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Sudah Check-in --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-green-50 border-b border-green-200 px-6 py-4 flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Sudah Check-in</h3>
                        <p class="text-xs text-gray-600">{{ $checkedIn->count() }} orang</p>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                    @forelse($checkedIn as $attendance)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-green-100 rounded-full flex items-center justify-center text-xs font-bold text-green-600">
                                        {{ substr($attendance->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ optional($attendance->schedule)->shift->shift_name ?? '-' }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium
                                    @if($attendance->status === 'telat')
                                        bg-orange-100 text-orange-800
                                    @elseif($attendance->status === 'izin')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($attendance->status === 'alpha')
                                        bg-red-100 text-red-800
                                    @else
                                        bg-green-100 text-green-800
                                    @endif
                                ">
                                    @if($attendance->status === 'telat')
                                        ⚠ Telat
                                    @elseif($attendance->status === 'izin')
                                        📋 Izin
                                    @elseif($attendance->status === 'alpha')
                                        ✗ Alpha
                                    @else
                                        ✓ Hadir
                                    @endif
                                </span>
                            </div>
                            <div class="text-xs text-gray-600 ml-12 mb-3">
                                <i data-lucide="clock" class="w-3 h-3 inline mr-1"></i> {{ $attendance->check_in_time->format('H:i') }}
                            </div>
                            <div class="flex gap-2 ml-12">
                                <a href="{{ route('operator.attendance.edit', $attendance) }}" class="text-xs px-2 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition-colors">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('operator.attendance.destroy', $attendance) }}" class="inline" onsubmit="return confirm('Hapus data?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                            <p class="text-sm">Tidak ada data</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Belum Check-in --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-red-50 border-b border-red-200 px-6 py-4 flex items-center gap-3">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-4 h-4 text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Belum Check-in</h3>
                        <p class="text-xs text-gray-600">{{ $notCheckedIn->count() }} orang</p>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                    @forelse($notCheckedIn as $schedule)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-red-100 rounded-full flex items-center justify-center text-xs font-bold text-red-600">
                                        {{ substr($schedule->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $schedule->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ optional($schedule->shift)->shift_name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 ml-12">
                                <form method="POST" action="{{ route('operator.attendance.mark-present') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                    <button type="submit" class="text-xs px-2 py-1 bg-green-100 text-green-600 rounded hover:bg-green-200 transition-colors">
                                        ✓ Hadir
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('operator.attendance.mark-leave') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                    <button type="submit" class="text-xs px-2 py-1 bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 transition-colors">
                                        Izin
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('operator.attendance.mark-absent') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                    <button type="submit" class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors">
                                        Alpha
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <i data-lucide="smile" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                            <p class="text-sm">Semua karyawan sudah check-in</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    if (window.lucide) {
        lucide.createIcons();
    }
</script>
@endpush
