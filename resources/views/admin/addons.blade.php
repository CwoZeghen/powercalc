<html lang="lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Addons</title>
    <link rel="stylesheet" href="{{ asset('css/b5.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.b5.css') }}">
</head>

<body>
    @include('admin.nav')
    <div class="container">
        <div class="row">
            <div class="col-md-12 pb-5">
                <h4 class="text-center mt-5 mb-5">Addons</h4>
                <div class="table-responsive">
                    <table table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Addons</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $el)
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>{{ $el->name }}</td>
                                    <td>{{ nf($el->cost) }} USD</td>
                                    <td>{{ $el->description }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="p-2">
                                                <button class="btn btn-outline-danger mr-3" data-bs-toggle="modal"
                                                    data-bs-target="#edit{{ $el->id }}">Edit</button>
                                            </div>
                                            <div class="p-2">
                                                <button class="btn btn-outline-danger" data-bs-toggle="modal"
                                                    data-bs-target="#del{{ $el->id }}">Delete</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">
                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#addAddons">New Addon</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addAddons" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"> Add a new addon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="was-validated" id="fappl">
                    @csrf
                    <div class="modal-body">
                        <div class="item">
                            <div class="d-flex">
                                <div class="form-floating m-2 w-50">
                                    <input required maxlength="100" class="form-control watts" id="floatingInput"
                                        name="name[]" placeholder="">
                                    <label for="floatingInput">Addon</label>
                                </div>
                                <div class="form-floating m-2 w-50">
                                    <input required type="number" min="0.01" step="0.01" class="form-control"
                                        id="floatingInput" name="cost[]" placeholder="">
                                    <label for="floatingInput">Price per month in USD</label>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="form-floating">
                                    <textarea style="height: 120px" maxlength="300" required name="description[]" class="form-control" placeholder=""
                                        id="floatingTextarea"></textarea>
                                    <label for="floatingTextarea">Description</label>
                                </div>
                            </div>
                        </div>
                        <div id="insertdiv"></div>
                        <button type="button" class="btn btn-link text-danger" id="add-appliance">
                            Add addon </button>
                        <div class="p-2 mt-3">
                            <div id="rep"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($data as $el)
        <div class="modal fade" id="edit{{ $el->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"> Edit addon</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form class="was-validated fedit">
                        <input type="hidden" name="id" value="{{ $el->id }}">
                        @csrf
                        <div class="modal-body">
                            <div class="d-flex item">
                                <div class="form-floating m-2">
                                    <input required value="{{ $el->name }}" class="form-control"
                                        id="floatingInput" name="name" placeholder="">
                                    <label for="floatingInput">Addon</label>
                                </div>
                                <div class="form-floating m-2">
                                    <input required type="number" value="{{ $el->cost }}" min="0.01"
                                        step="0.01" class="form-control" id="floatingInput" name="cost"
                                        placeholder="">
                                    <label for="floatingInput">Price per month in USD</label>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="form-floating">
                                    <textarea style="height: 120px" maxlength="300" required name="description" class="form-control" placeholder=""
                                        id="floatingTextarea">{{ $el->description }}</textarea>
                                    <label for="floatingTextarea">Description</label>
                                </div>
                            </div>
                            <div class="p-2 mt-3">
                                <div id="rep"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="del{{ $el->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"> Delete</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form class="was-validated fdel">
                        <input type="hidden" name="id" value="{{ $el->id }}">
                        <input type="hidden" name="type" value="notlisted">
                        @csrf
                        <div class="modal-body">
                            <b>Do you want to delete this addon : <i>{{ $el->name }}</i> ?</b>
                        </div>
                        <div class="p-2 mt-3">
                            <div id="rep"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">NO</button>
                            <button type="submit" class="btn btn-danger">YES</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script src="{{ asset('js/b5.js') }}"></script>
    <script src="{{ asset('js/jq.js') }}"></script>
    <script src="{{ asset('js/dt.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/dt.css') }}">

    <script>
        $(function() {
            let table = new DataTable('[table]');
            var template = `
                <div class="item">
                    <div class="d-flex">
                        <div class="form-floating w-50 m-2">
                            <input required maxlength="100" class="form-control watts" id="floatingInput"
                                name="name[]" placeholder="">
                            <label for="floatingInput">Addon</label>
                        </div>
                        <div class="form-floating w-50 m-2">
                            <input required type="number" min="0.01" step="0.01" class="form-control"
                                id="floatingInput" name="cost[]" placeholder="">
                            <label for="floatingInput">Price per month in USD</label>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="p-2 w-100">
                            <div class="form-floating d-flex">
                                <textarea style="height: 120px" required name="description[]" class="form-control" placeholder="" id="floatingTextarea"></textarea>
                                <label for="floatingTextarea">Description</label>
                                <div class="">
                                    <button type="button"
                                    class="btn btn-lg btn-danger remove btn-close text-danger mt-5 m-3"
                                    aria-label="Close">
                                </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>`;

            $('#add-appliance').click(function() {
                var data = $(template);
                $(data).insertBefore('#insertdiv');
                var btn = $('.remove', data);
                initRemove(btn);
            });

            function initRemove(item = null) {
                if (item == null) {
                    item = $('.remove');
                }
                item.click(function() {
                    item.closest('.item').remove();
                });
            }

            $('#fappl').submit(function() {
                event.preventDefault();
                var form = $(this);
                var rep = $('#rep', form);
                rep.slideUp();
                var data = form.serialize();
                $(':input', form).prop('disabled', true);
                $.ajax({
                    url: '{{ route('addons.store') }}',
                    type: 'POST',
                    data: data,
                    timeout: 20000,
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem(
                            "token"),
                    },
                    success: function(data) {
                        if (data.success) {
                            rep.html(data.message).removeClass().addClass('alert alert-success')
                                .slideDown();
                            form[0].reset();
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

            $('.fdel').submit(function() {
                event.preventDefault();
                var form = $(this);
                var rep = $('#rep', form);
                rep.slideUp();
                var data = form.serialize();
                var id = $('[name=id]', form).val();

                $.ajax({
                    url: '{{ route('addons.destroy', '') }}/' + id,
                    type: 'DELETE',
                    data: data,
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem(
                            "token"),
                    },
                    timeout: 20000,
                    success: function(data) {
                        rep.html(data.message).removeClass().addClass('alert alert-success')
                            .slideDown();
                        setTimeout(() => {
                            location.reload();
                        }, 3000);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        rep.html("Enable to reach the server").removeClass().addClass(
                                'alert alert-danger')
                            .slideDown();

                    }
                }).always(function() {
                    setTimeout(() => {
                        rep.slideUp();
                    }, 5000);
                })
            })

            $('.fedit').submit(function() {
                event.preventDefault();
                var form = $(this);
                var rep = $('#rep', form);

                var id = $('[name=id]', form).val();
                rep.slideUp();
                var data = form.serialize();
                $(':input', form).prop('disabled', true);
                $.ajax({
                    url: '{{ route('addons.update', '') }}/' + id,
                    type: 'PUT',
                    data: data,
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem(
                            "token"),
                    },
                    timeout: 20000,
                    success: function(data) {
                        if (data.success) {
                            rep.html(data.message).removeClass().addClass('alert alert-success')
                                .slideDown();
                            form[0].reset();
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            rep.html(data.message).removeClass().addClass('alert alert-danger')
                                .slideDown();
                        }

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
