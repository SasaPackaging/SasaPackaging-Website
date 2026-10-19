<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Creates the first admin (or resets an admin's password):
 *   php artisan admin:create
 * No password is stored in the code or in a seeder.
 */
class CreateAdmin extends Command
{
    protected $signature = 'admin:create
                            {--name= : Full name}
                            {--email= : Login email}
                            {--password= : Password (leave out to be asked safely)}';

    protected $description = 'Create an admin user, or reset the password if the email already exists';

    public function handle()
    {
        $name     = $this->option('name') ?: $this->ask('Full name');
        $email    = $this->option('email') ?: $this->ask('Email (used to log in)');
        $password = $this->option('password') ?: $this->secret('Password (minimum 8 characters)');

        $validator = Validator::make(
            ['name' => $name, 'email' => $email, 'password' => $password],
            ['name' => 'required|string|max:120', 'email' => 'required|email|max:150', 'password' => 'required|string|min:8']
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return 1;
        }

        $admin   = Admin::where('email', $email)->first();
        $existed = (bool) $admin;

        if (!$admin) {
            $admin = new Admin(['email' => $email]);
        }

        $admin->name     = $name;
        $admin->password = Hash::make($password);
        $admin->save();

        $this->info($existed ? 'Admin updated (password changed).' : 'Admin created.');
        $this->line('Login page: ' . url(config('admin.prefix') . '/login'));

        return 0;
    }
}
