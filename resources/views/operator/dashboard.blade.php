@extends('layouts.user')

@section('title', 'Dashboard - Operator')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4 mb-4 sm:mb-0">
                <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i data-lucide="shield" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Operator Dashboard</h1>
                    <p class="text-sm text-gray-600">Monitor & Manage Employee Attendance</p>
                </div>
            </div>
            <div class="text-sm font-medium text-gray-600">{{ $currentDate }}</div>
        </div>

        {{-- Quick Access Menu --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
            <a href="{{ route('operator.attendance.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow text-center">
                <i data-lucide="clipboard-list" class="w-6 h-6 text-sky-600 mx-auto mb-2"></i>
                <p class="text-sm font-medium text-gray-900">Kelola Absensi</p>
            </a>
            <a href="{{ route('operator.permissions.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow text-center">
                <i data-lucide="inbox" class="w-6 h-6 text-orange-600 mx-auto mb-2"></i>
                <p class="text-sm font-medium text-gray-900">Verifikasi Izin</p>
            </a>
            <a href="{{ route('operator.monitoring.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow text-center">
                <i data-lucide="activity" class="w-6 h-6 text-green-600 mx-auto mb-2"></i>
                <p class="text-sm font-medium text-gray-900">Monitoring</p>
            </a>
            <a href="{{ route('operator.reports.daily') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow text-center">
                <i data-lucide="calendar" class="w-6 h-6 text-purple-600 mx-auto mb-2"></i>
                <p class="text-sm font-medium text-gray-900">Laporan</p>
            </a>
            <a href="{{ route('operator.dashboard') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow text-center">
                <i data-lucide="refresh-cw" class="w-6 h-6 text-blue-600 mx-auto mb-2"></i>
                <p class="text-sm font-medium text-gray-900">Refresh</p>
            </a>
        </div>

        {{-- Main Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Users -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-500">Total</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                <p class="text-xs text-gray-500 mt-1">Karyawan Aktif</p>
            </div>

            <!-- Today Schedules -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="calendar" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-500">Hari Ini</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $todaySchedules }}</p>
                <p class="text-xs text-gray-500 mt-1">Jadwal Hari Ini</p>
            </div>

            <!-- Total Attendances (Month) -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="check-square" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-500">Bulan Ini</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalAttendances }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Kehadiran</p>
            </div>

            <!-- Pending Permissions -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="alert-circle" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-500">Pending</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $pendingPermissions }}</p>
                <p class="text-xs text-gray-500 mt-1">Pengajuan Izin</p>
            </div>
        </div>

        {{-- Today's Attendance Summary --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700">Hadir</p>
                        <p class="text-3xl font-bold text-green-900 mt-1">{{ $todayStats['hadir'] }}</p>
                    </div>
                    <i data-lucide="check-circle" class="w-10 h-10 text-green-600 opacity-20"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-700">Telat</p>
                        <p class="text-3xl font-bold text-orange-900 mt-1">{{ $todayStats['telat'] }}</p>
                    </div>
                    <i data-lucide="alert-circle" class="w-10 h-10 text-orange-600 opacity-20"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-700">Izin/Sakit</p>
                        <p class="text-3xl font-bold text-yellow-900 mt-1">{{ $todayStats['izin'] }}</p>
                    </div>
                    <i data-lucide="clock" class="w-10 h-10 text-yellow-600 opacity-20"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-700">Belum Check-in</p>
                        <p class="text-3xl font-bold text-red-900 mt-1">{{ $todayStats['alpha'] }}</p>
                    </div>
                    <i data-lucide="x-circle" class="w-10 h-10 text-red-600 opacity-20"></i>
                </div>
            </div>
        </div>

        {{-- Month Statistics & Chart --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-3 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 text-sky-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Trend Kehadiran Bulanan</h3>
                            <p class="text-xs text-gray-500">{{ $currentMonth }}</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('operator.dashboard') }}" class="flex gap-2">
                        <select name="selected_month" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-sky-500" onchange="this.form.submit()">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == $selectedMonth ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('M') }}
                                </option>
                            @endfor
                        </select>
                        <select name="selected_year" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-sky-500" onchange="this.form.submit()">
                            @for ($y = 2023; $y <= now()->year; $y++)
                                <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                </div>
                <div class="h-80">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Shift Distribution -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="pie-chart" class="w-4 h-4 text-sky-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900">Distribusi Shift</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Pagi</span>
                            <span class="font-bold text-blue-600">{{ $shiftPagi }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $totalSchedules > 0 ? ($shiftPagi / $totalSchedules * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Siang</span>
                            <span class="font-bold text-amber-600">{{ $shiftSiang }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $totalSchedules > 0 ? ($shiftSiang / $totalSchedules * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Malam</span>
                            <span class="font-bold text-purple-600">{{ $shiftMalam }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $totalSchedules > 0 ? ($shiftMalam / $totalSchedules * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">Total: {{ $totalSchedules }} jadwal</p>
            </div>
        </div>

        {{-- Two Column Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Absent Employees --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="user-x" class="w-4 h-4 text-red-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900">Top Absen Karyawan</h3>
                </div>
                @if($topAbsentEmployees->count() > 0)
                    <div class="space-y-3">
                        @foreach($topAbsentEmployees as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-xs font-bold text-red-600">
                                        {{ substr($item['user']->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $item['user']->name }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    {{ $item['count'] }} hari
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 text-gray-500">
                        <i data-lucide="smile" class="w-6 h-6 mx-auto mb-1 opacity-50"></i>
                        <p class="text-xs">Tidak ada data</p>
                    </div>
                @endif
            </div>

            {{-- Top Late Employees --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="clock-alert" class="w-4 h-4 text-orange-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900">Top Telat Karyawan</h3>
                </div>
                @if($topLateEmployees->count() > 0)
                    <div class="space-y-3">
                        @foreach($topLateEmployees as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-xs font-bold text-orange-600">
                                        {{ substr($item['user']->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $item['user']->name }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-800">
                                    {{ $item['count'] }} kali
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 text-gray-500">
                        <i data-lucide="smile" class="w-6 h-6 mx-auto mb-1 opacity-50"></i>
                        <p class="text-xs">Tidak ada data</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Attendances --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="list" class="w-4 h-4 text-sky-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Check-in Terbaru Hari Ini</h3>
            </div>
            
            @if($recentAttendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 px-2 font-medium text-gray-700">Karyawan</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-700">Shift</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-700">Check-in</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAttendances as $attendance)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-2 px-2">
                                        <p class="font-medium text-gray-900 text-xs">{{ $attendance->user->name }}</p>
                                    </td>
                                    <td class="py-2 px-2 text-gray-700 text-xs">{{ optional($attendance->schedule)->shift->shift_name ?? '-' }}</td>
                                    <td class="py-2 px-2 text-gray-700 text-xs">{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}</td>
                                    <td class="py-2 px-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            @if($attendance->status === 'alpha')
                                                bg-red-100 text-red-800
                                            @elseif($attendance->status === 'telat')
                                                bg-orange-100 text-orange-800
                                            @elseif($attendance->status === 'izin')
                                                bg-blue-100 text-blue-800
                                            @elseif($attendance->status === 'early_checkout')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($attendance->status === 'forgot_checkout')
                                                bg-purple-100 text-purple-800
                                            @else
                                                bg-green-100 text-green-800
                                            @endif
                                        ">
                                            @switch($attendance->status)
                                                @case('alpha')
                                                    Alpha
                                                @break
                                                @case('telat')
                                                    Telat
                                                @break
                                                @case('izin')
                                                    Izin
                                                @break
                                                @case('early_checkout')
                                                    Early Checkout
                                                @break
                                                @case('forgot_checkout')
                                                    Forgot Checkout
                                                @break
                                                @default
                                                    Tepat
                                            @endswitch
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i data-lucide="inbox" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                    <p class="text-gray-500 text-sm">Belum ada check-in hari ini</p>
                </div>
            @endif
        </div>

        {{-- Info Card --}}
        <div class="bg-gradient-to-r from-sky-50 to-blue-50 rounded-xl border border-sky-200 p-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-sky-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i data-lucide="lightbulb" class="w-4 h-4 text-white"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-sky-900 mb-1">Fitur Operator</h3>
                    <p class="text-sm text-sky-700">
                        Dashboard ini memudahkan Anda mengelola absensi harian, verifikasi izin/sakit karyawan, 
                        monitoring realtime check-in status, dan membuat laporan kehadiran per hari/minggu/bulan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceData = {!! json_encode($attendanceData) !!};
        
        const dates = attendanceData.map(d => d.day);
        
        const hádirData = attendanceData.map(d => d.hadir);
        const telatData = attendanceData.map(d => d.telat);
        const izinData = attendanceData.map(d => d.izin);
        const alphaData = attendanceData.map(d => d.alpha);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Hadir',
                        data: hádirData,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Telat',
                        data: telatData,
                        backgroundColor: '#f59e0b',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Izin',
                        data: izinData,
                        backgroundColor: '#eab308',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Alpha',
                        data: alphaData,
                        backgroundColor: '#ef4444',
                        borderRadius: 6,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 12, weight: '500' },
                            padding: 15,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 11 }
                        },
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
        
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endpush


@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4 mb-4 sm:mb-0">
                <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i data-lucide="shield" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Operator Dashboard</h1>
                    <p class="text-sm text-gray-600">Monitor, Analyze & Report Employee Attendance</p>
                </div>
            </div>
            <div class="text-sm font-medium text-gray-600">{{ $currentDate }}</div>
        </div>

        {{-- Main Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Users -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-500">Total</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                <p class="text-xs text-gray-500 mt-1">Active Employees</p>
            </div>

            <!-- Today Schedules -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="calendar" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-500">Today</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $todaySchedules }}</p>
                <p class="text-xs text-gray-500 mt-1">Scheduled Today</p>
            </div>

            <!-- Total Attendances (Month) -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="check-square" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-500">{{ $selectedMonth }}/{{ $selectedYear }}</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalAttendances }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Attendances</p>
            </div>

            <!-- Pending Permissions -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="alert-circle" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-500">Pending</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $pendingPermissions }}</p>
                <p class="text-xs text-gray-500 mt-1">Permission Requests</p>
            </div>
        </div>

        {{-- Today's Attendance Summary --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700">Hadir</p>
                        <p class="text-3xl font-bold text-green-900 mt-1">{{ $todayStats['hadir'] }}</p>
                    </div>
                    <i data-lucide="check-circle" class="w-10 h-10 text-green-600 opacity-20"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-700">Telat</p>
                        <p class="text-3xl font-bold text-orange-900 mt-1">{{ $todayStats['telat'] }}</p>
                    </div>
                    <i data-lucide="alert-circle" class="w-10 h-10 text-orange-600 opacity-20"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-700">Izin</p>
                        <p class="text-3xl font-bold text-yellow-900 mt-1">{{ $todayStats['izin'] }}</p>
                    </div>
                    <i data-lucide="clock" class="w-10 h-10 text-yellow-600 opacity-20"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-700">Alpha</p>
                        <p class="text-3xl font-bold text-red-900 mt-1">{{ $todayStats['alpha'] }}</p>
                    </div>
                    <i data-lucide="x-circle" class="w-10 h-10 text-red-600 opacity-20"></i>
                </div>
            </div>
        </div>

        {{-- Month Statistics & Chart --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-3 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 text-sky-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Monthly Attendance Trend</h3>
                            <p class="text-xs text-gray-500">{{ $currentMonth }}</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('operator.dashboard') }}" class="flex gap-2">
                        <select name="selected_month" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-sky-500" onchange="this.form.submit()">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == $selectedMonth ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('M') }}
                                </option>
                            @endfor
                        </select>
                        <select name="selected_year" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-sky-500" onchange="this.form.submit()">
                            @for ($y = 2023; $y <= now()->year; $y++)
                                <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                </div>
                <div class="h-80">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Shift Distribution -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="pie-chart" class="w-4 h-4 text-sky-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900">Shift Distribution</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Pagi</span>
                            <span class="font-bold text-blue-600">{{ $shiftPagi }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $totalSchedules > 0 ? ($shiftPagi / $totalSchedules * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Siang</span>
                            <span class="font-bold text-amber-600">{{ $shiftSiang }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $totalSchedules > 0 ? ($shiftSiang / $totalSchedules * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Malam</span>
                            <span class="font-bold text-purple-600">{{ $shiftMalam }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $totalSchedules > 0 ? ($shiftMalam / $totalSchedules * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">Total: {{ $totalSchedules }} schedules</p>
            </div>
        </div>

        {{-- Two Column Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Absent Employees --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="user-x" class="w-4 h-4 text-red-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900">Top Absent Employees</h3>
                </div>
                @if($topAbsentEmployees->count() > 0)
                    <div class="space-y-3">
                        @foreach($topAbsentEmployees as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-xs font-bold text-red-600">
                                        {{ substr($item['user']->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $item['user']->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item['user']->email }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    {{ $item['count'] }} days
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i data-lucide="smile" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                        <p class="text-sm">No absent records</p>
                    </div>
                @endif
            </div>

            {{-- Top Late Employees --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="clock-alert" class="w-4 h-4 text-orange-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900">Top Late Employees</h3>
                </div>
                @if($topLateEmployees->count() > 0)
                    <div class="space-y-3">
                        @foreach($topLateEmployees as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-xs font-bold text-orange-600">
                                        {{ substr($item['user']->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $item['user']->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item['user']->email }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-800">
                                    {{ $item['count'] }} times
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i data-lucide="smile" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                        <p class="text-sm">No late records</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pending Permissions Requests --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="inbox" class="w-4 h-4 text-orange-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Recent Permission Requests</h3>
                @if($pendingPermissions > 0)
                    <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-800">
                        {{ $pendingPermissions }} Pending
                    </span>
                @endif
            </div>
            
            @if($permissionRequests->count() > 0)
                <div class="space-y-2">
                    @foreach($permissionRequests as $request)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="flex-shrink-0">
                                    @if($request->type === 'izin')
                                        <i data-lucide="shield-alert" class="w-5 h-5 text-yellow-600"></i>
                                    @elseif($request->type === 'cuti')
                                        <i data-lucide="calendar-x" class="w-5 h-5 text-red-600"></i>
                                    @else
                                        <i data-lucide="clock" class="w-5 h-5 text-blue-600"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">{{ $request->user->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ ucfirst($request->type) }} - 
                                        {{ $request->schedule ? $request->schedule->schedule_date->format('d M Y') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                Pending
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i data-lucide="inbox" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                    <p class="text-gray-500">No pending permission requests</p>
                </div>
            @endif
        </div>

        {{-- Recent Check-ins --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="list" class="w-4 h-4 text-sky-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Today's Recent Check-ins</h3>
            </div>
            
            @if($recentAttendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-700">Employee</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-700">Shift</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-700">Check-in</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAttendances as $attendance)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <p class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $attendance->user->email }}</p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <p class="text-sm text-gray-700">{{ optional($attendance->schedule)->shift->shift_name ?? 'N/A' }}</p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <p class="text-sm text-gray-700">{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}</p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($attendance->status === 'alpha')
                                                bg-red-100 text-red-800
                                            @elseif($attendance->status === 'telat')
                                                bg-orange-100 text-orange-800
                                            @elseif($attendance->status === 'izin')
                                                bg-blue-100 text-blue-800
                                            @elseif($attendance->status === 'early_checkout')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($attendance->status === 'forgot_checkout')
                                                bg-purple-100 text-purple-800
                                            @else
                                                bg-green-100 text-green-800
                                            @endif
                                        ">
                                            @switch($attendance->status)
                                                @case('alpha')
                                                    <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i> Alpha
                                                @break
                                                @case('telat')
                                                    <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> Telat
                                                @break
                                                @case('izin')
                                                    <i data-lucide="file-text" class="w-3 h-3 mr-1"></i> Izin
                                                @break
                                                @case('early_checkout')
                                                    <i data-lucide="log-out" class="w-3 h-3 mr-1"></i> Early Out
                                                @break
                                                @case('forgot_checkout')
                                                    <i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i> Forgot Out
                                                @break
                                                @default
                                                    <i data-lucide="check" class="w-3 h-3 mr-1"></i> On Time
                                            @endswitch
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                    <p class="text-gray-500">No check-ins recorded today</p>
                </div>
            @endif
        </div>

        {{-- Info Card --}}
        <div class="bg-gradient-to-r from-sky-50 to-blue-50 rounded-xl border border-sky-200 p-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-sky-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i data-lucide="lightbulb" class="w-4 h-4 text-white"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-sky-900 mb-1">Operator Capabilities</h3>
                    <p class="text-sm text-sky-700">
                        As an operator, you can monitor employee attendance in real-time, view analytics and trends, 
                        identify top absent and late employees, and manage pending permission requests. 
                        You have broader visibility than regular employees but limited management capabilities compared to admins.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceData = {!! json_encode($attendanceData) !!};
        
        const dates = attendanceData.map(d => d.day);
        
        const hádirData = attendanceData.map(d => d.hadir);
        const telatData = attendanceData.map(d => d.telat);
        const izinData = attendanceData.map(d => d.izin);
        const alphaData = attendanceData.map(d => d.alpha);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Hadir',
                        data: hádirData,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Telat',
                        data: telatData,
                        backgroundColor: '#f59e0b',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Izin',
                        data: izinData,
                        backgroundColor: '#eab308',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Alpha',
                        data: alphaData,
                        backgroundColor: '#ef4444',
                        borderRadius: 6,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 12, weight: '500' },
                            padding: 15,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 11 }
                        },
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
        
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endpush


@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-white">
    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        {{-- Hero Welcome Section --}}
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-sky-500 rounded-xl flex items-center justify-center">
                    <i data-lucide="shield" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ $operator->name }}!</h1>
                    <p class="text-sm text-gray-600">Operator Dashboard - Monitor attendance and employee schedules</p>
                </div>
            </div>
        </div>

        {{-- Quick Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                        <p class="text-[11px] text-gray-500 mt-1">Employees</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Today's Schedules</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $todaySchedules }}</p>
                        <p class="text-[11px] text-gray-500 mt-1">Total scheduled</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="calendar" class="w-6 h-6 text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Hadir</p>
                        <p class="text-2xl font-bold text-green-600">{{ $todayStats['hadir'] }}</p>
                        <p class="text-[11px] text-gray-500 mt-1">Present</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Izin</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $todayStats['izin'] }}</p>
                        <p class="text-[11px] text-gray-500 mt-1">Permission</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Alpha</p>
                        <p class="text-2xl font-bold text-red-600">{{ $todayStats['alpha'] }}</p>
                        <p class="text-[11px] text-gray-500 mt-1">Absent</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly Shift Distribution --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="sunrise" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Shift Pagi</p>
                            <p class="text-xs text-gray-500">This month</p>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-blue-600">{{ $shiftPagi }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="sun" class="w-5 h-5 text-amber-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Shift Siang</p>
                            <p class="text-xs text-gray-500">This month</p>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-amber-600">{{ $shiftSiang }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="moon" class="w-5 h-5 text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Shift Malam</p>
                            <p class="text-xs text-gray-500">This month</p>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-purple-600">{{ $shiftMalam }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="info" class="w-5 h-5 text-sky-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Current</p>
                            <p class="text-xs text-gray-500">{{ $currentMonth }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance Status Info --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="info" class="w-4 h-4 text-sky-600"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900">Today's Attendance Summary</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i data-lucide="check-circle" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-green-700">Hadir (Present)</div>
                        <div class="text-lg font-bold text-green-900">{{ $todayStats['hadir'] }}</div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-orange-700">Telat (Late)</div>
                        <div class="text-lg font-bold text-orange-900">{{ $todayStats['telat'] }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i data-lucide="clock" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-yellow-700">Izin (Permission)</div>
                        <div class="text-lg font-bold text-yellow-900">{{ $todayStats['izin'] }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-red-50 to-red-100 rounded-lg border border-red-200">
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i data-lucide="x-circle" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-red-700">Alpha (Absent)</div>
                        <div class="text-lg font-bold text-red-900">{{ $todayStats['alpha'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly Attendance Chart (JavaScript Chart) --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-sky-600"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Monthly Attendance Overview</h3>
                    <p class="text-xs text-gray-500">{{ $currentMonth }}</p>
                </div>
            </div>
            <div id="attendanceChart" class="w-full h-96"></div>
        </div>

        {{-- Recent Attendances --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="list" class="w-4 h-4 text-sky-600"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900">Today's Recent Check-ins</h3>
            </div>
            
            @if($recentAttendances->count() > 0)
                <div class="space-y-3">
                    @foreach($recentAttendances as $attendance)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-10 h-10 bg-sky-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="user" class="w-5 h-5 text-sky-600"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $attendance->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ optional($attendance->schedule)->shift->shift_name ?? 'N/A' }} - 
                                        {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : 'No check-in' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($attendance->status === 'alpha')
                                        bg-red-100 text-red-800
                                    @elseif($attendance->status === 'telat')
                                        bg-orange-100 text-orange-800
                                    @elseif($attendance->status === 'izin')
                                        bg-blue-100 text-blue-800
                                    @elseif($attendance->status === 'early_checkout')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($attendance->status === 'forgot_checkout')
                                        bg-purple-100 text-purple-800
                                    @else
                                        bg-green-100 text-green-800
                                    @endif
                                ">
                                    @switch($attendance->status)
                                        @case('alpha')
                                            <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i> Alpha
                                        @break
                                        @case('telat')
                                            <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> Telat
                                        @break
                                        @case('izin')
                                            <i data-lucide="file-text" class="w-3 h-3 mr-1"></i> Izin
                                        @break
                                        @case('early_checkout')
                                            <i data-lucide="log-out" class="w-3 h-3 mr-1"></i> Early Out
                                        @break
                                        @case('forgot_checkout')
                                            <i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i> Forgot Out
                                        @break
                                        @default
                                            <i data-lucide="check" class="w-3 h-3 mr-1"></i> On Time
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                    <p class="text-gray-500">No attendance records for today</p>
                </div>
            @endif
        </div>

        {{-- Info Box --}}
        <div class="bg-gradient-to-r from-sky-50 to-blue-50 rounded-xl border border-sky-200 p-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-sky-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i data-lucide="lightbulb" class="w-4 h-4 text-white"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-sky-900 mb-1">Information</h3>
                    <p class="text-sm text-sky-700">
                        As an operator, you can monitor employee attendance and schedules in real-time. 
                        This dashboard provides a comprehensive overview of daily attendance status and monthly trends.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Attendance Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceData = {!! json_encode($attendanceData) !!};
        
        const dates = attendanceData.map(d => d.day);
        
        const hádirData = attendanceData.map(d => d.hadir);
        const telatData = attendanceData.map(d => d.telat);
        const izinData = attendanceData.map(d => d.izin);
        const alphaData = attendanceData.map(d => d.alpha);
        
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Hadir',
                        data: hádirData,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Telat',
                        data: telatData,
                        backgroundColor: '#f59e0b',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Izin',
                        data: izinData,
                        backgroundColor: '#eab308',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Alpha',
                        data: alphaData,
                        backgroundColor: '#ef4444',
                        borderRadius: 6,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            padding: 15,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Initialize Lucide icons
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endpush
