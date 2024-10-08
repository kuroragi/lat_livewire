<?php

namespace App\Livewire\Master;

use App\Helpers;
use App\Models\Roles;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Role extends Component
{
    public $roles;
    public $isEditMode = false;

    public $id_role;
    #[Validate('required', message: 'Nama role tidak boleh kosong')]
    public $name;
    public $slug;

    public function mount(){
        $this->roles = Roles::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.master.role');
    }

    public function save(){
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Helpers::getSlug($this->name),
        ];

        $updateOrCreate = Roles::updateOrCreate(['id' => $this->id_role], $data);

        if($updateOrCreate){
            $this->resetField();
            $this->reloadData();
            $this->isEditMode ? session()->flash('warning', 'Berhasil merubah data role') : session()->flash('success', 'Berhasil menambah data role');
            $this->isEditMode = false;
            
        }
    }

    public function edit($id){
        $role = Roles::findOrFail($id);

        if($role){
            $this->isEditMode = true;
            $this->id_role = $role->id;
            $this->name = $role->name;
            $this->slug = $role->slug;
        }
    }

    public function delete($id){
        $role = Roles::findOrFail($id);
        if($role){
            Roles::destroy('id', $role->id);
            $this->reloadData();
        }
    }

    public function reloadData(){
        $this->roles = Roles::orderBy('name')->get();
    }

    public function resetField(){
        $this->reset(['id_role', 'name', 'slug']);
    }
}
