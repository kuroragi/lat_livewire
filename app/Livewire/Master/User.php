<?php

namespace App\Livewire\Master;

use App\Models\Roles;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class User extends Component
{
    public $users;
    public $roles;
    public $isEditMode;

    public $id_user;
    #[Validate('required')]
    public $name;
    public $role_slug;
    #[Validate('required|email')]
    public $email;
    #[Validate('nullable')]
    public $password;

    public function mount(){
        $this->users = ModelsUser::with(['getRole'])->get();
        $this->roles = Roles::orderBy('name')->get();
    }

    #[Layout('layouts.app', ['page_title' => 'Data User'])]
    public function render()
    {
        return view('livewire.master.user');
    }

    public function save(){
        $this->validate();

        $passwordHash = $this->password ? Hash::make($this->password) : null;

        $data = [
            'name' => $this->name,
            'role_slug' => $this->role_slug,
            'email' => $this->email,
            'password' => $passwordHash ?? $this->user->password,
        ];

        $updateOrCreate = ModelsUser::updateOrCreate(['id' => $this->id_user], $data);

        if($updateOrCreate){
            $this->resetField();
            $this->reloadData();
            $this->isEditMode ? session()->flash('warning', 'Berhasil merubah data user') : session()->flash('success', 'Berhasil menambah data user');
            $this->isEditMode = false;
            
        }
    }

    public function edit($id){
        $user = ModelsUser::findOrFail($id);

        if($user){
            $this->isEditMode = true;
            $this->id_user = $user->id;
            $this->name = $user->name;
            $this->role_slug = $user->role_slug;
            $this->email = $user->email;
            $this->password = $user->password;
        }
    }

    public function delete($id){
        $user = ModelsUser::findOrFail($id);
        if($user){
            ModelsUser::destroy('id', $user->id);
            $this->reloadData();
        }
    }

    public function reloadData(){
        $this->users = ModelsUser::with(['getRole'])->get();
    }

    public function resetField(){
        $this->reset(['id_user', 'name', 'role_slug', 'email', 'password']);
    }
}
