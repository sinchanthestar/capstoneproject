@extends('layouts.user')

@section('title', 'Verifikasi Izin/Sakit')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        {{-- Header --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                <i data-lucide="inbox" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Verifikasi Izin/Sakit</h1>
                <p class="text-sm text-gray-600">Kelola pengajuan izin dan sakit dari karyawan</p>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex gap-2 mb-6 border-b border-gray-200">
            <a href="{{ route('operator.permissions.index', ['status' => 'pending']) }}" class="px-4 py-2 font-medium {{ $status === 'pending' ? 'border-b-2 border-sky-600 text-sky-600' : 'text-gray-600 hover:text-gray-900' }}">
                <i data-lucide="clock" class="w-4 h-4 inline mr-2"></i>
                Pending ({{ $permissions->where('status', 'pending')->count() }})
            </a>
            <a href="{{ route('operator.permissions.index', ['status' => 'approved']) }}" class="px-4 py-2 font-medium {{ $status === 'approved' ? 'border-b-2 border-sky-600 text-sky-600' : 'text-gray-600 hover:text-gray-900' }}">
                <i data-lucide="check" class="w-4 h-4 inline mr-2"></i>
                Disetujui
            </a>
            <a href="{{ route('operator.permissions.index', ['status' => 'rejected']) }}" class="px-4 py-2 font-medium {{ $status === 'rejected' ? 'border-b-2 border-sky-600 text-sky-600' : 'text-gray-600 hover:text-gray-900' }}">
                <i data-lucide="x" class="w-4 h-4 inline mr-2"></i>
                Ditolak
            </a>
            <a href="{{ route('operator.permissions.index', ['status' => 'all']) }}" class="px-4 py-2 font-medium {{ $status === 'all' ? 'border-b-2 border-sky-600 text-sky-600' : 'text-gray-600 hover:text-gray-900' }}">
                <i data-lucide="list" class="w-4 h-4 inline mr-2"></i>
                Semua
            </a>
        </div>

        {{-- Permission List --}}
        @if($permissions->count() > 0)
            <div class="space-y-3">
                @foreach($permissions as $permission)
                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 bg-sky-100 rounded-full flex items-center justify-center text-sm font-bold text-sky-600">
                                        {{ substr($permission->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $permission->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $permission->user->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-3 space-y-1 text-sm text-gray-600">
                                    <p><span class="font-medium">Tipe:</span> {{ ucfirst($permission->type) }}</p>
                                    <p><span class="font-medium">Tanggal:</span> {{ $permission->schedule?->schedule_date->format('d M Y') ?? '-' }}</p>
                                    <p><span class="font-medium">Shift:</span> {{ $permission->schedule?->shift->shift_name ?? '-' }}</p>
                                    <p><span class="font-medium">Alasan:</span> {{ $permission->reason ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2 flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($permission->status === 'pending')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($permission->status === 'approved')
                                        bg-green-100 text-green-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ ucfirst($permission->status) }}
                                </span>

                                @if($permission->status === 'pending')
                                    <a href="{{ route('operator.permissions.show', $permission) }}" class="text-xs px-3 py-1.5 bg-sky-100 text-sky-700 rounded hover:bg-sky-200 text-center font-medium">
                                        Review
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $permissions->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-gray-500">Tidak ada pengajuan dengan status ini</p>
            </div>
        @endif
    </div>
</div>
@endsection
