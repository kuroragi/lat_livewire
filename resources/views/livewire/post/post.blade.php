<div class="card">
    <div class="card-header">
        <h3 class="card-title">DataTable with default features</h3>
    </div>

    <div class="card-body">
        <button class="btn btn-sm btn-outline-primary mb-3" data-toggle="modal" data-target="#postModal"
            wire:click='resetField'><i class="fa fa-plus"></i> Tambah Post</button>

        <div class="card">
            <div class="card-body">

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>image(s)</th>
                            <th data-dt-order="disable">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $post->content_title }}</td>
                                <td>{{ $post->postCategory->name }}</td>
                                <td><img src="/storage/{{ $post->header_image }}" width="100" height="100"
                                        style="object-fit:contain;" alt=""></td>
                                <td>
                                    <button wire:click='EditPost({{ $post->id }})'
                                        class="btn btn-outline-warning btn-sm" title="Edit" data-toggle="modal"
                                        data-target="#postModal">Edit <i class="fa fa-edit"></i></button>
                                    <a href="/post-detail/{{ $post->id }}"><button
                                            class="btn btn-outline-info btn-sm" title="Detail">Detail <i
                                                class="fa fa-search"></i></button></a>
                                    <button wire:click='DeletePost({{ $post->id }})'
                                        class="btn btn-outline-danger btn-sm" title="Detail">Hapus <i
                                            class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Image(s)</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="postModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Extra Large Modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-cancel="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <div class="mb-3">
                                <label for="content_title" class="form-label">Title</label>
                                <input wire:model='content_title' type="text" name="content_title" id="content_title"
                                    class="form-control">
                                @error('content_title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="post_category" class="form-label">Category</label>
                                <select wire:model='post_category' class="form-control" id="post_category">
                                    <option value="{{ $post_categories[0]->slug }}">
                                        {{ $post_categories[0]->name }}</option>
                                    @foreach ($post_categories as $post_category)
                                        <option wire:key='{{ $post_category->slug }}'
                                            value="{{ $post_category->slug }}">{{ $post_category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3" wire:ignore>
                                <label for="content" class="form-label">Content</label>
                                <textarea wire:model.lazy='content' id="content"></textarea>
                                <trix-editor input="content" id="trixEditor"></trix-editor>
                                @error('content')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="header_image" class="form-label">Header Image</label>
                                <div class="mb-2">
                                    @if ($header_image instanceof \Livewire\TemporaryUploadedFile)
                                        <img src="{{ $header_image->temporaryUrl() }}" width="250" height="250"
                                            style="object-fit:contain;" alt="">
                                    @elseif($header_image)
                                        <img src="/storage/{{ $header_image }}" width="250" height="250"
                                            style="object-fit:contain;" alt="">
                                    @endif
                                </div>
                                <div class="input-group">
                                    <input wire:model='header_image' type="file" name="header_image"
                                        id="header_image" class="custom-file-input">
                                    <label for="" class="custom-file-label">Browse</label>
                                </div>
                                @error('header_image')
                                    <span class="text-sm text-danger">{{ $message }}</span>
                                @enderror
                                <!-- Progress Bar -->
                                <div x-show="uploading">
                                    <progress max="100" x-bind:value="progress"></progress>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100" wire:click.prevent='save'><i
                                    class="fa fa-floppy-disk"></i>
                                Simpan</button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let isModalClosed = false;
        Livewire.on('dismiss_modal', () => {
            $ {
                "#postModal"
            }.modal('hide');
            isModalClosed = true;
            setTimeout(() => {
                isModalClosed = false;
            }, 1000);
        });

        Livewire.on('editModal', () => {
            setTimeout(() => {
                let trixEditor = $('#trixEditor');
                let content = $('#content');

                trixEditor.html(content.val());
            }, 100);

        });

        Livewire.on('resetField', () => {
            $("#trixEditor").html("");
        })

        // Mendengarkan event `trix-change` untuk sinkronisasi data Trix dengan Livewire
        document.addEventListener('trix-change', function(e) {
            @this.set('content', e.target.value);
        });

        document.addEventListener('trix-attachment-add', function(event) {

            if (event.attachment.file) {
                uploadImage(event.attachment);
            }
        });

        document.addEventListener('trix-attachment-remove', function(event) {
            if (isModalClosed = false) {
                removeImage(event.attachment);
            }
        })

        function uploadImage(attachment) {

            if (attachment.file) {
                var formData = new FormData();
                formData.append('attachment', attachment.file);

                $.ajax({
                    url: '/attachments', // Endpoint for handling file upload
                    type: 'POST',
                    data: formData, // Mengirim FormData dengan file
                    contentType: false, // Ini penting agar FormData dapat mengelola tipe file
                    processData: false, // Mencegah jQuery mengubah data menjadi string
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token untuk keamanan
                    },
                    success: function(response) {

                        if (response.url) {
                            // Set the uploaded file's URL to Trix editor
                            attachment.setAttributes({
                                url: response.url,
                                href: response.url
                            });

                            // Membuat elemen gambar dengan class dan style
                            // Temukan elemen gambar setelah berhasil diupload
                            const imgElements = document.querySelectorAll('trix-editor img[src="' + response
                                .url + '"]');

                            imgElements.forEach(imgElement => {
                                imgElement.classList.add('w-100', 'px-5'); // Tambahkan class
                                imgElement.style.objectFit = 'contain'; // Tambahkan style
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Upload error:', error); // Menangani error upload
                    }
                });
            }
        }

        function removeImage(attachment) {
            if (attachment.getAttribute('url')) {
                let fileUrl = attachment.getAttribute('url');

                $.ajax({
                    type: "POST",
                    url: "/attachments/delete",
                    data: {
                        file_url: fileUrl
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token untuk keamanan
                    },
                    success: function(response) {
                        console.log(response);

                        console.log('Data removed Successfully');

                    },
                    error: function(xhr, status, error) {
                        console.error('Remove error:', error); // Menangani error upload
                    }
                });
            }
        }
    </script>
@endpush
