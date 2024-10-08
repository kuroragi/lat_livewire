<div>
    <div class="card">
        <div class="card-body">
            <button wire:click='resetField'class="btn btn-outline-primary mb-3" data-toggle="modal"
                data-target="#userModal"><i class="fas fa-plus"></i>
                Tambah</button>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> warning!</h5>
                    {{ session('warning') }}
                </div>
            @endif

            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <th>No</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr wire:key='{{ $user->email }}'>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->getRole->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <button wire:click='edit({{ $user->id }})' class="btn btn-outline-warning btn-sm"
                                    data-toggle="modal" data-target="#userModal"><i class="fas fa-edit"></i>
                                    Edit</button>
                                <button wire:click='delete({{ $user->id }})'
                                    wire:confirm.prompt='Yakin untuk menghapus user ini?\n\nKetik USER-{{ $user->name }} untuk konfirmasi|USER-{{ $user->slug }}'
                                    class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i>
                                    Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfooter>
                    <th>No</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Action</th>
                </tfooter>
            </table>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="userModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-{{ $isEditMode ? 'warning' : 'primary' }}">
                    <h4 class="modal-title">{{ $isEditMode ? 'Edit' : 'Tambah' }} Role</h4>
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
                    
                    <div class="mb-3">
                        <label for="role_slug" class="form-label">role_slug</label>
                        <select wire:model='role_slug' id="role_slug" class="form-control">
                            <option value="{{ $roles[0]->slug }}">--- Pilih Role ---</option>
                            @foreach ($roles as $role)
                                <option wire:key='{{ $role->slug }}' value="{{ $role->slug }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_slug')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input wire:model='email' type="email" id="email" class="form-control">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input wire:model='password' type="password" id="password" class="form-control">
                            <button class="btn btn-secondary" id="togglePassword"><i class="fas fa-eye"></i></button>
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button wire:click.prevent='save' class="btn btn-{{ $isEditMode ? 'warning' : 'success' }} w-100"
                        data-dismiss="modal"><i class="fas fa-floppy-disk"></i> Simpan
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
            $("#togglePassword").on("click", function(){
                let passwordInput = $("#password");
                let icon = $("#iconPass");

                if (passwordInput.attr("type") == 'password') {
                    passwordInput.attr("type", "text");
                    icon.removeClass("fa-eye")
                    icon.addClass("fa-eye-slash")
                } else {
                    passwordInput.attr("type", "password");
                    icon.addClass("fa-eye")
                    icon.removeClass("fa-eye-slash")
                }
            })
        })
    </script>
@endpush
