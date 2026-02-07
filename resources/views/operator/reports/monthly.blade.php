@extends('layouts.user')

@section('title', 'Laporan Bulanan')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        {{-- Header --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                <i data-lucide="bar-chart-2" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Bulanan</h1>
                <p class="text-sm text-gray-600">Rekapitulasi kehadiran per bulan</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('operator.reports.monthly') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                    <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                        @for ($y = 2023; $y <= now()->year; $y++)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
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

        {{-- Data Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Karyawan</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Total Jadwal</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Hadir</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Telat</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Absen</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Persentase</th>
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
                                <td class="px-6 py-4 text-center">
                                    <p class="font-semibold text-gray-900">{{ $item['total_scheduled'] }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ $item['attended'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                        {{ $item['late'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        {{ $item['absent'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-12 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-sky-500 rounded-full" style="width: {{ $item['percentage'] }}%"></div>
                                        </div>
                                        <span class="font-semibold text-gray-900">{{ $item['percentage'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                    <p>Tidak ada data untuk bulan ini</p>
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
