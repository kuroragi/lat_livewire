<?php

namespace App\Livewire\Post;

use App\Models\PostCategories;
use App\Models\Posts;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('layouts.app', ['page_title' => 'Post'])]
#[Title('Post')]
class Post extends Component
{
    use WithFileUploads;

    public $posts;
    public $post_categories;
    public $isEditMode = false;

    public $id_post;
    #[Validate('required', message: 'Title tidak boleh kosong')]
    #[Validate('min:5', message: 'Title tidak boleh kurang dari 5')]
    public $title;
    #[Validate('required', message: "Content tidak boleh kosong")]
    public $content;
    public $post_category;
    #[Validate('nullable|image|max:10240')]
    public $header_image;
    public $author;

    public $listener = ['dismiss_modal'];

    public function mount(){
        $this->posts = Posts::with('postCategory')->orderBy('created_at', 'Desc')->get();
        $this->post_categories = PostCategories::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.post.post');
    }

    public function save(){
        $validated = $this->validate();

        if(is_string($this->header_image)){
            $path = $this->header_image;
        }else{
            $validated['header_image'] = 'image|max:10240';
        
            if($this->header_image instanceof TemporaryUploadedFile){
                $name = $this->header_image->getClientOriginalName();
                $path = $this->header_image->storeAs('header_image', $name, 'public');
            }
        }

        // Hapus seluruh tag <figcaption> beserta isinya
        $contentWithoutFigcaption = preg_replace('/<figcaption[^>]*>.*?<\/figcaption>/is', '', $this->content);
        
        // Hapus atribut width dari tag <img>
        $contentWithoutWidth = preg_replace('/<img([^>]*?)\swidth="[^"]*"([^>]*)>/i', '<img$1$3>', $contentWithoutFigcaption);

        // Hapus atribut height dari tag <img>
        $contentWithoutDimensions = preg_replace('/<img([^>]*?)\sheight="[^"]*"([^>]*)>/i', '<img$1$3>', $contentWithoutWidth);

        $modifiedContent = preg_replace(
            '/<img([^>]*)>/',
            '<img$1 class="w-100 py-3 px-5" style="object-fit: contain;" />',
            $contentWithoutDimensions
        );

        $data = [
            'title' => $this->title,
            'content' => $modifiedContent,
            'header_image' => $path,
            'author' => '',
            'post_category' => $this->post_category,
        ];

        dd($data);

        $updateOrCreate = Posts::updateOrCreate(['id' => $this->id_post], $data);

        if($updateOrCreate){
            $this->resetField();
            $this->reloadData();
            $this->isEditMode ? session()->flash('warning', 'Berhasil merubah data post') : session()->flash('success', 'Berhasil menambah post');
            $this->isEditMode = false;
            $this->dispatch('dismiss_modal');
            
        }
    }

    public function edit($id){
        $post = Posts::findOrFail($id);

        if($post){
            $this->isEditMode = true;
            $this->id_post = $post->id;
            $this->title = $post->title;
            $this->content = $post->content;
            $this->header_image = $post->header_image;
            $this->author = $post->author;
            $this->post_category = $post->post_category;
        }
    }

    public function delete($id){
        $post = Posts::findOrFail($id);
        if($post){
            Posts::destroy('id', $post->id);
            $this->reloadData();
        }
    }

    public function reloadData(){
        $this->posts = Posts::with('postCategory')->orderBy('created_at', 'Desc')->get();
    }

    public function resetField(){
        $this->reset(['id_post', 'title', 'content', 'header_image', 'author', 'post_category']);
        $this->dispatch('resetField');
    }
}
