<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetupOperator extends Command
{
    protected $signature = 'setup:operator {--email=operator@lintasarta.com} {--password=password123}';
    protected $description = 'Create or update an Operator user account';

    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        
        $user = User::where('email', $email)->first();
        
        if ($user) {
            $user->update([
                'role' => 'Operator',
                'password' => bcrypt($password)
            ]);
            $this->info("✅ Updated operator user: {$email}");
        } else {
            User::create([
                'name' => 'Operator User',
                'email' => $email,
                'password' => bcrypt($password),
                'role' => 'Operator',
                'email_verified_at' => now(),
            ]);
            $this->info("✅ Created new operator user: {$email}");
        }
        
        $this->info("📧 Email: {$email}");
        $this->info("🔑 Password: {$password}");
        $this->newLine();
        $this->info("You can now login and access: http://127.0.0.1:8000/operator/attendance");
    }
}
