<?php

namespace App\Livewire\Forms\Auth;

use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate(['username' => 'required|min:3'], message: [
        'username.required' => 'Username harus di isi',
        'username.min' => 'Username minimal 3 karakter',
    ])]
    public string $username = "";

    #[Validate(['password' => 'required|min:8'], message: [
        'password.required' => 'Username harus di isi',
        'password.min' => 'Username minimal 8 karakter',
    ])]
    public string $password = "";

    #[Validate(['remember' => 'nullable|boolean'], message: [])]
    public bool $remember = false;
}
