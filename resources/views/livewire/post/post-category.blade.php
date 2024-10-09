<div>
    <div class="card">
        <div class="card-body">
            <button wire:click='resetField'class="btn btn-outline-primary mb-2" data-toggle="modal"
                data-target="#postCategoryModal"><i class="fas fa-plus"></i>
                Tambah</button>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible mb-2">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible mb-2">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> warning!</h5>
                    {{ session('warning') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">

                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <th>No</th>
                            <th>Category</th>
                            <th data-dt-order="disable">Action</th>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr wire:key='{{ $category->slug }}'>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <button wire:click='edit({{ $category->id }})'
                                            class="btn btn-outline-warning btn-sm" data-toggle="modal"
                                            data-target="#postCategoryModal"><i class="fas fa-edit"></i>
                                            Edit</button>
                                        <button wire:click='delete({{ $category->id }})'
                                            wire:confirm.prompt='Yakin untuk menghapusr category ini?\n\nKetik Category untuk konfirmasi|Category'
                                            class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i>
                                            Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfooter>
                            <th>No</th>
                            <th>category</th>
                            <th>Action</th>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="postCategoryModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-{{ $isEditMode ? 'warning' : 'primary' }}">
                    <h4 class="modal-title">{{ $isEditMode ? 'Edit' : 'Tambah' }} category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input wire:model='name' type="text" id="name" class="form-control">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button wire:click.prevent='save'
                        class="btn btn-{{ $isEditMode ? 'warning' : 'success' }} w-100"><i
                            class="fas fa-floppy-disk"></i> Simpan
                        {{ $isEditMode ? 'Perubahan' : '' }}</button>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {

        });

        Livewire.on('dismiss_modal', function() {
            $("#postCategoryModal").modal('hide');
        })
    </script>
@endpush
