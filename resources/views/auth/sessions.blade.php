@extends('layouts.admin')

@section('title', 'Sesi Aktif')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 sm:p-6 lg:p-8">
    <div class="mx-auto max-w-4xl space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-monitor text-green-600">
                        <rect width="20" height="14" x="2" y="3" rx="2"/>
                        <line x1="8" x2="16" y1="21" y2="21"/>
                        <line x1="12" x2="12" y1="17" y2="21"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Sesi Aktif</h1>
                    <p class="text-gray-500 mt-1">Kelola sesi login dan perangkat yang aktif</p>
                </div>
            </div>
            <button onclick="refreshSessions()" 
                class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition-all duration-200 transform   focus:outline-none focus:ring-4 focus:ring-gray-200 shadow-lg hover:shadow-xl border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-refresh-cw mr-2">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                    <path d="M21 3v5h-5"/>
                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                    <path d="M3 21v-5h5"/>
                </svg>
                Muat Ulang
            </button>
        </div>

        <!-- Security Alert -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shield-check text-blue-600">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="M9 12l2 2 4-4"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Pemberitahuan Keamanan</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Periksa sesi aktif secara berkala. Jika ada perangkat atau lokasi yang tidak dikenal, hentikan sesi tersebut dan ganti kata sandi Anda.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions List -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Sesi Aktif Anda</h2>
                        <p class="text-gray-600 mt-1">Sesi aktif dalam 2 jam terakhir</p>
                    </div>
                    <button onclick="logoutAllSessions()" 
                        class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-semibold text-sm rounded-lg transition-all duration-200  ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-log-out mr-2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16,17 21,12 16,7"/>
                            <line x1="21" x2="9" y1="12" y2="12"/>
                        </svg>
                        Keluar dari Semua Sesi
                    </button>
                </div>
            </div>

            <div id="sessionsContainer" class="p-8">
                <div class="flex items-center justify-center py-12">
                    <svg class="w-8 h-8 animate-spin text-gray-400 mr-3" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-500">Memuat sesi...</span>
                </div>
            </div>
        </div>

        <!-- Remember Me Tokens -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Perangkat Ingat Saya</h2>
                        <p class="text-gray-600 mt-1">Perangkat dengan token "Ingat Saya" aktif</p>
                    </div>
                    <button onclick="revokeAllTokens()" 
                        class="inline-flex items-center px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 font-semibold text-sm rounded-lg transition-all duration-200  ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-shield-x mr-2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <path d="M9.5 9l5 5"/>
                            <path d="M14.5 9l-5 5"/>
                        </svg>
                        Cabut Semua Token
                    </button>
                </div>
            </div>

            <div id="tokensContainer" class="p-8">
                <div class="flex items-center justify-center py-12">
                    <svg class="w-8 h-8 animate-spin text-gray-400 mr-3" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-500">Memuat token...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSessions();
    loadTokens();
});

async function loadSessions() {
    try {
        const response = await fetch('/auth/sessions');
        const data = await response.json();
        
        const container = document.getElementById('sessionsContainer');
        
        if (data.sessions && data.sessions.length > 0) {
            container.innerHTML = data.sessions.map(session => `
                <div class="border border-gray-200 rounded-lg p-6 mb-4 ${session.is_current ? 'bg-green-50 border-green-200' : 'bg-white'}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-10 h-10 bg-gradient-to-br ${session.is_current ? 'from-green-400 to-green-500' : 'from-gray-400 to-gray-500'} rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-monitor text-white">
                                        <rect width="20" height="14" x="2" y="3" rx="2"/>
                                        <line x1="8" x2="16" y1="21" y2="21"/>
                                        <line x1="12" x2="12" y1="17" y2="21"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">${session.location}</h3>
                                    <p class="text-sm text-gray-600">${session.ip_address}</p>
                                </div>
                                ${session.is_current ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Sesi Saat Ini</span>' : ''}
                                ${session.is_trusted ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Perangkat Tepercaya</span>' : ''}
                            </div>
                            <div class="text-sm text-gray-500 space-y-1">
                                <p><strong>Agen Pengguna:</strong> ${session.user_agent.substring(0, 80)}...</p>
                                <p><strong>Aktivitas Terakhir:</strong> ${session.last_activity}</p>
                            </div>
                        </div>
                        ${!session.is_current ? `
                            <button onclick="terminateSession(${session.id})" 
                                class="inline-flex items-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-semibold text-sm rounded-lg transition-all duration-200  ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-x mr-1">
                                    <path d="M18 6 6 18"/>
                                    <path d="M6 6l12 12"/>
                                </svg>
                                Hentikan
                            </button>
                        ` : ''}
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-monitor-off mx-auto text-gray-400 mb-4">
                        <path d="M17 17H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v6"/>
                        <path d="M21 21H3"/>
                        <path d="M7 21h10"/>
                        <path d="M12 17v4"/>
                        <path d="M17 17l5 5"/>
                        <path d="M17 22l5-5"/>
                    </svg>
                    <p class="text-gray-500">Tidak ada sesi aktif</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Gagal memuat sesi:', error);
        document.getElementById('sessionsContainer').innerHTML = `
            <div class="text-center py-12">
                <p class="text-red-500">Gagal memuat sesi</p>
            </div>
        `;
    }
}

async function loadTokens() {
    // This would load remember me tokens - implementation depends on your API
    document.getElementById('tokensContainer').innerHTML = `
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-key mx-auto text-gray-400 mb-4">
                <circle cx="7.5" cy="15.5" r="5.5"/>
                <path d="M21 2l-9.6 9.6"/>
                <path d="M15.5 7.5l3 3L22 7l-3-3"/>
            </svg>
            <p class="text-gray-500">Pengelolaan token akan segera tersedia</p>
        </div>
    `;
}

async function terminateSession(sessionId) {
    if (!confirm('Yakin ingin menghentikan sesi ini?')) return;
    
    try {
        const response = await fetch(`/auth/sessions/${sessionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            loadSessions(); // Muat ulang sesi
        } else {
            alert('Gagal menghentikan sesi');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menghentikan sesi');
    }
}

async function logoutAllSessions() {
    if (!confirm('Ini akan mengeluarkan Anda dari semua perangkat. Lanjutkan?')) return;
    
    try {
        const response = await fetch('/auth/logout-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            window.location.href = '/login';
        } else {
            alert('Gagal keluar dari semua sesi');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal keluar dari semua sesi');
    }
}

function refreshSessions() {
    loadSessions();
    loadTokens();
}

function revokeAllTokens() {
    if (!confirm('Ini akan mencabut semua token "Ingat Saya". Lanjutkan?')) return;
    // Implementation for revoking all tokens
    alert('Fitur akan segera tersedia');
}
</script>
@endsection
