<nav class="navbar navbar-expand-lg sticky-top bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{ route('admin.home') }}">Appliances</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{ route('admin.addons') }}">Addons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{ route('admin.contact') }}">Contact</a>
                </li>
                <li class="nav-item">
                    <button data-bs-toggle="modal" data-bs-target="#costmdl" class="nav-link" aria-current="page"
                        href="#">Settings</button>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="modal fade" id="costmdl" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"> Edit cost</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="was-validated" id="fcost">
                @csrf
                <div class="modal-body">
                    <div class="d-flex">
                        @php
                            $setting = \App\Models\Appsetting::first();
                            if (!$setting) {
                                \App\Models\Appsetting::create(['cost' => 0.05, 'peakload' => 0.18]);
                                $cost = 0.05;
                                $peakload = 0.18;
                            }

                            $cost = (float) @$setting->cost;
                            $peakload = (float) @$setting->peakload;
                        @endphp
                        <div class="form-floating w-100">
                            <input required value="{{ $cost }}" type="number" min="0.01" step="0.01"
                                class="form-control w-100" id="floatingInput" name="cost" placeholder="">
                            <label for="floatingInput">1 kW = </label>
                        </div>
                        <label class="input-group-text" for="floatingInput">USD</label>
                    </div>
                    <div class="form-floating mt-3">
                        <input required value="{{ $peakload }}" type="number" min="0.01" max="1"
                            step="0.01" class="form-control watts" id="floatingInput" name="peakload" placeholder="">
                        <label for="floatingInput">Ratio of Peak load on Total Watt Hours per Day = </label>
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
<script src="{{ asset('js/jq.js') }}"></script>
<script>
    $(function() {
        $('#fcost').submit(function() {
            event.preventDefault();
            var form = $(this);
            var rep = $('#rep', form);
            rep.slideUp();
            var data = form.serialize();

            $.ajax({
                url: '{{ route('data.cost.save') }}',
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
