<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateUserCommand extends Command
{
    protected $signature = 'user:create
        {--name= : User name}
        {--email= : User email address}
        {--phone= : User phone number}
        {--admin : Create an administrator}
        {--unapproved : Require administrator approval before login}';

    protected $description = 'Create an application user using a securely entered password';

    public function handle(): int
    {
        $name = trim((string) ($this->option('name') ?: $this->ask('Name')));
        $email = mb_strtolower(trim((string) ($this->option('email') ?: $this->ask('Email'))));
        $phone = trim((string) ($this->option('phone') ?: $this->ask('Phone number')));
        $password = (string) $this->secret('Password');
        $passwordConfirmation = (string) $this->secret('Repeat password');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:user,email'],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::INVALID;
        }

        $user = DB::transaction(function () use ($name, $email, $phone, $password): User {
            $user = new User([
                'name' => $name,
                'email' => $email,
                'phone_number' => $phone,
                'password' => Hash::make($password),
            ]);
            $user->forceFill([
                'approved' => ! $this->option('unapproved'),
                'isAdmin' => (bool) $this->option('admin'),
                'email_verified_at' => now(),
            ]);
            $user->save();

            return $user;
        });

        $role = $user->isAdministrator() ? 'administrator' : 'user';
        $this->info("Created {$role} #{$user->getKey()} ({$user->email}).");

        if (! $user->approved) {
            $this->warn('The account is not approved and cannot log in yet.');
        }

        return self::SUCCESS;
    }
}
