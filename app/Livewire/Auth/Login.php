<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\Auth\LoginForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    public LoginForm $form;

    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.basic');
    }

    public function authenticate(){
        $this->form->validate();
        $credentials = [
            'username' => $this->form->username,
            'password' => $this->form->password,
        ];

        if(Auth::attempt($credentials, $this->form->remember)){
            return $this->redirect('/',  navigate: true);
        }

        $this->addError('login-status', 'Username atau password salah');
    }
}
