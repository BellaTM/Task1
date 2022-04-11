<?php

namespace App\Http\Livewire;
use App\Models\Post;
use Livewire\Component;

class PostCrud extends Component
{
    public $posts, $title, $desc, $post_id;
    public $isModalOpen = 0;

    public function render() //untuk menampilkan view dari file post-crud.blade.php
    {
        $this->posts = Post::all();
        return view('livewire.post-crud');
    }

    public function create() //untuk memanggil method resetCreateForm untuk me-reset field dan method openModal untuk menampilkan modal yang berisi form input untuk tambah atau edit data.
    {
        $this->resetCreateForm();
        $this->openModal();
    }

    public function openModal() //untuk menampilkan modal
    {
        $this->isModalOpen = true;
    }

    public function closeModal() //untuk menutup modal
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm(){ //digunakan untuk me-reset form input title dan desc
        $this->title = '';
        $this->desc = '';
    }
    
    public function store() //method ini dijalankan saat menambahkan atau edit data. Dan di dalam method ini terdapat perintah validasi, resetCreateForm dan closeModal.
    {
        $this->validate([
            'title' => 'required',
            'desc' => 'required',
        ]);
    
        Post::updateOrCreate(['id' => $this->post_id], [
            'title' => $this->title,
            'desc' => $this->desc,
        ]);

        session()->flash('message', $this->post_id ? 'Data updated successfully.' : 'Data added successfully.');

        $this->closeModal();
        $this->resetCreateForm();
    }

    public function edit($id) //untuk menampilkan modal dengan form input yang terisi data-data yang akan diedit.
    {
        $post = Post::findOrFail($id);
        $this->post_id = $id;
        $this->title = $post->title;
        $this->desc = $post->desc;
    
        $this->openModal();
    }
    
    public function delete($id) //dijalankan untuk perintah menghapus data dengan parameter id, kemudian menampilkan session()->flash() dengan pesan "Data deleted successfully".
    {
        Post::find($id)->delete();
        session()->flash('message', 'Data deleted successfully.');
    }

}
