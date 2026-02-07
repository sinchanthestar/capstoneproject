@extends('layouts.user')

@section('title', 'Laporan Harian')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        {{-- Header --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                <i data-lucide="bar-chart-2" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Harian</h1>
                <p class="text-sm text-gray-600">Rekapitulasi absensi per hari</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('operator.reports.daily') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ $date }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div class="flex gap-2 items-end">
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-sm text-gray-600 mb-1">Total Jadwal</p>
                <p class="text-3xl font-bold text-gray-900">{{ $summary['total_scheduled'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-sm text-gray-600 mb-1">Hadir</p>
                <p class="text-3xl font-bold text-green-600">{{ $summary['present'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-sm text-gray-600 mb-1">Telat</p>
                <p class="text-3xl font-bold text-orange-600">{{ $summary['late'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-sm text-gray-600 mb-1">Absen</p>
                <p class="text-3xl font-bold text-red-600">{{ $summary['absent'] }}</p>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Karyawan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Shift</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Check-in</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Check-out</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($data as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item['user']->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item['user']->email }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $item['shift']->shift_name }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $item['check_in_time'] ? $item['check_in_time']->format('H:i') : '-' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $item['check_out_time'] ? $item['check_out_time']->format('H:i') : '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($item['status'] === 'alpha')
                                            bg-red-100 text-red-800
                                        @elseif($item['is_late'])
                                            bg-orange-100 text-orange-800
                                        @else
                                            bg-green-100 text-green-800
                                        @endif
                                    ">
                                        @if($item['status'] === 'alpha')
                                            Alpha
                                        @elseif($item['is_late'])
                                            Telat
                                        @else
                                            Hadir
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                    <p>Tidak ada data untuk tanggal ini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
