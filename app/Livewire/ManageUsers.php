<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class ManageUsers extends Component
{
    use WithPagination;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public bool $is_admin = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'is_admin' => 'boolean',
    ];

    public function createUser(): void
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_admin' => $this->is_admin,
        ]);

        $this->reset(['name', 'email', 'password', 'is_admin']);
        session()->flash('status', 'Account created.');
    }

    public function render()
    {
        return view('livewire.manage-users', [
            'users' => User::orderBy('created_at', 'desc')->paginate(10),
        ]);
    }
}

