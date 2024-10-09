<?php

namespace App\Livewire\Post;

use App\Helpers;
use App\Models\PostCategories;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app', ['page_title' => 'Post Category'])]
#[Title('Post Categories')]
class PostCategory extends Component
{
    public $categories;
    public $isEditMode = false;

    public $id_post_category;
    #[Validate('required', message: 'Nama role tidak boleh kosong')]
    public $name;
    public $slug;

    public $listener = ['dismiss_modal'];

    public function mount(){
        $this->categories = PostCategories::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.post.post-category');
    }

    public function save(){
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Helpers::getSlug($this->name),
        ];

        $updateOrCreate = PostCategories::updateOrCreate(['id' => $this->id_post_category], $data);

        if($updateOrCreate){
            $this->resetField();
            $this->reloadData();
            $this->isEditMode ? session()->flash('warning', 'Berhasil merubah data post category') : session()->flash('success', 'Berhasil menambah post category');
            $this->isEditMode = false;
            $this->dispatch('dismiss_modal');
            
        }
    }

    public function edit($id){
        $role = PostCategories::findOrFail($id);

        if($role){
            $this->isEditMode = true;
            $this->id_post_category = $role->id;
            $this->name = $role->name;
            $this->slug = $role->slug;
        }
    }

    public function delete($id){
        $role = PostCategories::findOrFail($id);
        if($role){
            PostCategories::destroy('id', $role->id);
            $this->reloadData();
        }
    }

    public function reloadData(){
        $this->categories = PostCategories::orderBy('name')->get();
    }

    public function resetField(){
        $this->reset(['id_post_category', 'name', 'slug']);
    }
}
