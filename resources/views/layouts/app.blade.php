<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>

    @include('layouts.partials._head')

    @livewireStyles
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            @include('layouts.partials._header')
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            @include('layouts.partials._sidemenu')
        </aside>

        <div class="content-wrapper">
            @include('layouts.partials._page_header')

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            {{ $slot }}
                            {{-- @yield('content') --}}
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            @include('layouts.partials._footer')
        </footer>

        <aside class="control-sidebar control-sidebar-dark">

        </aside>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                {{ session('success') }}
            </div>
        @endif

    </div>


    @include('layouts.partials._foot')

    @livewireScripts
    @stack('scripts')

    {{-- <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["Add", "copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script> --}}
</body>

</html>
