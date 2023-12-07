<html lang="lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Login</title>
    <link rel="stylesheet" href="{{ asset('css/b5.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.b5.css') }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    {{-- <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Home</a>
                    </li> --}}
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-12 pb-5">
                <h4 class="text-center mt-5">Login</h4>
                <form class="mt-3" id="form" method="POST" action="#">
                    @csrf
                    <div class="row flex-column">
                        <div class="col-md-4 offset-md-4">
                            <div class="form-floating m-2">
                                <input class="form-control" name="email" value="{{ old('email') }}"
                                    id="floatingInput" placeholder="">
                                <label for="floatingInput">Email</label>
                            </div>
                        </div>
                        <div class="col-md-4 offset-md-4">
                            <div class="form-floating m-2">
                                <input class="form-control" type="password" name="password" id="floatingInput"
                                    placeholder="">
                                <label for="floatingInput">Password</label>
                            </div>
                            <div class="p-2">
                                <div id="rep"></div>
                            </div>
                            <div class="p-2">
                                <button class="btn btn-danger">Login</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/b5.js') }}"></script>
    <script src="{{ asset('js/jq.js') }}"></script>

    <script>
        $(function() {
            $('#form').submit(function() {
                event.preventDefault();
                var form = $(this);
                var rep = $('#rep', form);
                rep.slideUp();
                var data = form.serialize();
                $(':input', form).prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.login') }}',
                    type: 'POST',
                    data: data,
                    success: function(data) {
                        if (data.success) {
                            rep.html(data.message).removeClass().addClass('alert alert-success')
                                .slideDown();
                            form[0].reset();
                            localStorage.setItem('token', data.token);
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            rep.html(data.message).removeClass().addClass('alert alert-danger')
                                .slideDown();
                        }
                        $(':input', form).prop('disabled', false);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        rep.html("Enable to reach the server").removeClass().addClass(
                                'alert alert-danger')
                            .slideDown();

                    }
                }).always(function() {
                    $(':input', form).prop('disabled', false);
                    setTimeout(() => {
                        rep.slideUp();
                    }, 5000);
                })
            })
        })
    </script>
</body>

</html>
