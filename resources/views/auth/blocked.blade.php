<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Diblokir - Peringatan Keamanan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-red-50 min-h-screen flex items-center justify-center">
    <div class="max-w-auto w-auto mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Meme Image -->
            <div class="w-128 h-85 mx-auto mb-6">
                <img src="{{ asset('kairiemote.jpg') }}" alt="Meme Diblokir" class="w-full h-full object-cover rounded-lg shadow-md">
            </div>
            
            <!-- Warning Icon -->
            <div class="w-16 h-16 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="shield-x" class="w-8 h-8 text-red-600"></i>
            </div>
            
            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Akses Diblokir</h1>
            
            <!-- Message -->
            <div class="text-gray-600 mb-6 space-y-3">
                <p class="text-sm">Alamat IP Anda diblokir sementara karena aktivitas mencurigakan.</p>
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-left">
                    <div class="flex items-center mb-2">
                        <i data-lucide="info" class="w-4 h-4 text-red-600 mr-2"></i>
                        <span class="font-medium text-red-800">Detail Pemblokiran</span>
                    </div>
                    <div class="text-sm text-red-700 space-y-1">
                        <p><strong>Alasan:</strong> {{ $blockInfo->reason }}</p>
                        <p><strong>Diblokir Pada:</strong> {{ $blockInfo->blocked_at->format('d M Y, H:i') }}</p>
                        @if($timeRemaining)
                            <p><strong>Sisa Waktu:</strong> {{ $timeRemaining }} menit</p>
                        @elseif($blockInfo->is_permanent)
                            <p><strong>Status:</strong> <span class="text-red-800 font-medium">Diblokir Permanen</span></p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
                <div class="flex items-center mb-2">
                    <i data-lucide="lightbulb" class="w-4 h-4 text-blue-600 mr-2"></i>
                    <span class="font-medium text-blue-800">Apa yang bisa Anda lakukan?</span>
                </div>
                <div class="text-sm text-blue-700 space-y-1">
                    @if($timeRemaining)
                        <p>• Tunggu {{ $timeRemaining }} menit dan coba lagi</p>
                        <p>• Pastikan Anda menggunakan kredensial login yang benar</p>
                        <p>• Hubungi administrator sistem jika ini dirasa keliru</p>
                    @elseif($blockInfo->is_permanent)
                        <p>• Segera hubungi administrator sistem</p>
                        <p>• Sertakan alamat IP Anda untuk investigasi</p>
                    @else
                        <p>• Coba lagi nanti</p>
                        <p>• Hubungi administrator sistem bila diperlukan</p>
                    @endif
                </div>
            </div>
            
            <!-- Actions -->
            <div class="space-y-3">
                @if($timeRemaining)
                    <div class="text-sm text-gray-500">
                        <p>Halaman ini akan memuat ulang otomatis dalam <span id="countdown">{{ $timeRemaining * 60 }}</span> detik</p>
                    </div>
                @endif
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick="window.location.reload()" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                        Muat Ulang Halaman
                    </button>
                    
                    <a href="mailto:admin@company.com" 
                       class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                        Hubungi Admin
                    </a>
                </div>
            </div>
            
            <!-- Security Notice -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-center text-xs text-gray-500">
                    <i data-lucide="shield-check" class="w-3 h-3 mr-1"></i>
                    <span>Langkah keamanan ini melindungi sistem dari akses tidak sah</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            @if($timeRemaining)
            // Countdown timer
            let timeLeft = {{ $timeRemaining * 60 }};
            const countdownElement = document.getElementById('countdown');
            
            const timer = setInterval(function() {
                timeLeft--;
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    window.location.reload();
                    return;
                }
                
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }, 1000);
            @endif
        });
    </script>
</body>
</html>
