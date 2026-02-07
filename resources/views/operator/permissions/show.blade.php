@extends('layouts.user')

@section('title', 'Detail Pengajuan Izin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-2xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('operator.permissions.index') }}" class="text-sky-600 hover:text-sky-700">
                    <i data-lucide="arrow-left" class="w-6 h-6"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pengajuan</h1>
                    <p class="text-sm text-gray-600">Review dan proses pengajuan izin/sakit</p>
                </div>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mt-0.5"></i>
                    <p class="font-medium text-green-900">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Details Card --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $permission->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $permission->user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Pengajuan</label>
                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst($permission->type) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $permission->schedule?->schedule_date->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $permission->schedule?->shift->shift_name ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan</label>
                        <p class="text-gray-900">{{ $permission->reason ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            @if($permission->status === 'pending')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Approve Form --}}
                    <form action="{{ route('operator.permissions.approve', $permission) }}" method="POST" class="bg-green-50 rounded-xl border border-green-200 p-6">
                        @csrf
                        <h3 class="text-lg font-semibold text-green-900 mb-4">
                            <i data-lucide="check-circle" class="w-5 h-5 inline mr-2"></i>
                            Setujui Pengajuan
                        </h3>
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-green-900 mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Catatan persetujuan..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                            Setujui
                        </button>
                    </form>

                    {{-- Reject Form --}}
                    <form action="{{ route('operator.permissions.reject', $permission) }}" method="POST" class="bg-red-50 rounded-xl border border-red-200 p-6">
                        @csrf
                        <h3 class="text-lg font-semibold text-red-900 mb-4">
                            <i data-lucide="x-circle" class="w-5 h-5 inline mr-2"></i>
                            Tolak Pengajuan
                        </h3>
                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-red-900 mb-2">Alasan Penolakan</label>
                            <textarea name="reason" id="reason" rows="3" class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500" placeholder="Alasan penolakan..." required></textarea>
                            @error('reason')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium" onclick="return confirm('Tolak pengajuan ini?')">
                            Tolak
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center">
                    <p class="text-gray-600">Pengajuan ini sudah di-{{ $permission->status }}</p>
                    <a href="{{ route('operator.permissions.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        Kembali
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
