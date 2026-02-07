<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'successful',
        'failure_reason',
        'attempted_at',
    ];

    protected $casts = [
        'successful' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    /**
     * Get the user that owns the login attempt (if exists)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * Record a login attempt
     */
    public static function record(string $email, string $ipAddress, string $userAgent, bool $successful, ?string $failureReason = null): self
    {
        // Check if table exists first
        if (!Schema::hasTable('login_attempts')) {
            return new self();
        }

        return self::create([
            'email' => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'successful' => $successful,
            'failure_reason' => $failureReason,
            'attempted_at' => now(),
        ]);
    }

    /**
     * Get failed attempts for email in last X minutes
     */
    public static function getFailedAttemptsForEmail(string $email, int $minutes = 15): int
    {
        // Check if table exists first
        if (!Schema::hasTable('login_attempts')) {
            return 0;
        }

        return self::where('email', $email)
            ->where('successful', false)
            ->where('attempted_at', '>=', Carbon::now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Get failed attempts for IP in last X minutes
     */
    public static function getFailedAttemptsForIP(string $ipAddress, int $minutes = 15): int
    {
        // Check if table exists first
        if (!Schema::hasTable('login_attempts')) {
            return 0;
        }

        return self::where('ip_address', $ipAddress)
            ->where('successful', false)
            ->where('attempted_at', '>=', Carbon::now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Check if email is locked out
     */
    public static function isEmailLockedOut(string $email, int $maxAttempts = 5, int $lockoutMinutes = 30): bool
    {
        $failedAttempts = self::getFailedAttemptsForEmail($email, $lockoutMinutes);
        return $failedAttempts >= $maxAttempts;
    }

    /**
     * Check if IP is locked out
     */
    public static function isIPLockedOut(string $ipAddress, int $maxAttempts = 10, int $lockoutMinutes = 60): bool
    {
        $failedAttempts = self::getFailedAttemptsForIP($ipAddress, $lockoutMinutes);
        return $failedAttempts >= $maxAttempts;
    }

    /**
     * Clear successful attempts for email
     */
    public static function clearSuccessfulAttempts(string $email): void
    {
        if (!Schema::hasTable('login_attempts')) {
            return;
        }

        self::where('email', $email)
            ->where('successful', false)
            ->where('attempted_at', '>=', Carbon::now()->subHours(24))
            ->delete();
    }

    /**
     * Get lockout time remaining for email
     */
    public static function getLockoutTimeRemaining(string $email, int $lockoutMinutes = 30): ?int
    {
        if (!Schema::hasTable('login_attempts')) {
            return null;
        }

        $lastFailedAttempt = self::where('email', $email)
            ->where('successful', false)
            ->orderBy('attempted_at', 'desc')
            ->first();

        if (!$lastFailedAttempt) {
            return null;
        }

        $lockoutUntil = $lastFailedAttempt->attempted_at->addMinutes($lockoutMinutes);
        $now = Carbon::now();

        if ($now->lt($lockoutUntil)) {
            return $now->diffInMinutes($lockoutUntil);
        }

        return null;
    }
}
