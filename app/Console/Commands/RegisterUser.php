<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class RegisterUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:register-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Enter name');
        $email = $this->ask('Enter email');
        $password = $this->secret('Enter password');
        $errors = $this->validateUserData($name, $email, $password);
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->error($error);
            }
            return;
        }
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("User '{$user->name}' with email '{$user->email}' has been registered successfully.");
    }

    private function validateUserData($name, $email, $password) {
        $errors = [];
        if (empty($name) || strlen($name) > 255) {
            $errors[] = "Name is required and must be less than 255 characters.";
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "A valid email is required.";
        } elseif (User::where('email', $email)->exists()) {
            $errors[] = "Email already exists.";
        }
        if (empty($password) || strlen($password) < 8) {
            $errors[] = "Password is required and must be at least 8 characters.";
        }
        return $errors;
    }
}
