<html lang="lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Contact</title>
    <link rel="stylesheet" href="{{ asset('css/b5.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.b5.css') }}">
</head>

<body>
    @include('admin.nav')
    <div class="container">
        <div class="row">
            <div class="col-md-12 pb-5">
                <h4 class="text-center mt-5 mb-5">Contact</h4>
                <div class="table-responsive">
                    <table table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>User</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Email status</th>
                                <th>Address</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $el)
                                @php
                                    $d = (object) json_decode($el->address);
                                    $address = @$d->address;
                                    $la = @$d->lat;
                                    $lo = @$d->lon;
                                    $url = "http://maps.google.com/?q=$la,$lo";
                                @endphp
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>{{ $el->name }}</td>
                                    <td>{{ $el->phone ?? '-' }}</td>
                                    <td>{{ $el->email ?? '-' }}</td>
                                    <td>
                                        @if ($el->email_verified_at)
                                            <span class="badge bg-success">VERIFIED</span>
                                        @else
                                            <span class="badge bg-danger">NOT VERIFIED</span>
                                        @endif
                                    </td>
                                    <td>{{ $address }}
                                        <br>
                                        <a target="_blank" href="{{ $url }}" class="btn btn-link">
                                            <i>Open in map</i>
                                        </a>
                                    </td>
                                    <td>{{ $el->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <button class="btn btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#info{{ $el->id }}">Receipt</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addAppliance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"> Add a new appliance</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="was-validated" id="fappl">
                    @csrf
                    <div class="modal-body">
                        <div class="d-flex item">
                            <div class="form-floating m-2">
                                <input required maxlength="100" class="form-control watts" id="floatingInput"
                                    name="appliance[]" placeholder="Appliance">
                                <label for="floatingInput">Appliance</label>
                            </div>
                            <div class="form-floating m-2">
                                <input required type="number" min="0.01" step="0.01" class="form-control watts"
                                    id="floatingInput" name="watts[]" placeholder="Watts (Volts x Amps)">
                                <label for="floatingInput">Watts (Volts x Amps)</label>
                            </div>
                        </div>
                        <div id="insertdiv"></div>
                        <button type="button" class="btn btn-link text-danger" id="add-appliance">Add
                            appliance</button>
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
        <div class="modal fade" id="info{{ $el->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"> Receipt</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @php
                            $d = (object) json_decode($el->appliance);
                            $addons = (array) @$d->addons;
                            $image = (array) @$d->image;
                            $appliance = (array) @$d->appliance;
                            $watts = (array) @$d->watts;
                            $qty = (array) @$d->qty;
                            $onperday = (array) @$d->onperday;
                            $totwattspeak = $totwattsday = $totwattsmonth = 0;
                        @endphp
                        <table class="table table-striped table-hover ">
                            @php
                                $d = (object) json_decode($el->address);
                                $address = @$d->address;
                            @endphp
                            <thead>
                                <tr>
                                    <th colspan="6">
                                        <b>Customer</b>
                                    </th>
                                </tr>
                            </thead>
                            <tr>
                                <td>Name</td>
                                <td colspan="5" class="text-end">{{ $el->name }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td colspan="5" class="text-end">{{ $el->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td colspan="5" class="text-end">{{ $el->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td colspan="5" class="text-end">{{ $address }}</td>
                            </tr>
                        </table>
                        <table class="table table-striped table-hover mt-3">
                            <thead>
                                <tr>
                                    <th colspan="6">
                                        <b>Items</b>
                                    </th>
                                </tr>
                            </thead>
                            <tr>
                                <th></th>
                                <th>Image</th>
                                <th>Appliance</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Watts</th>
                                <th class="text-center">Hours On per day</th>
                                <th class="text-end">Watts hours per day</th>
                            </tr>
                            @foreach ($appliance as $k => $v)
                                @php
                                    $img = @$image[$k];
                                    $_qty = (int) $qty[$k];
                                    $_watts = (float) $watts[$k];
                                    $_onperday = (float) $onperday[$k];
                                    $_totwatts = $_qty * $_watts * $_onperday;
                                    $totwattsday += $_totwatts;
                                @endphp
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>
                                        <img src="{{ $img }}" class="img-thumbnail image-appl"
                                            alt="image"
                                            style="cursor: pointer;width: 100px; height: 60px; object-fit:cover">
                                    </td>
                                    <td>{{ $v }}</td>
                                    <td class="text-center">{{ $_qty }}</td>
                                    <td class="text-center">{{ nf($_watts) }} {{ $_watts > 1 ? 'Watts' : 'Watt' }}
                                    </td>
                                    <td class="text-center">{{ $_onperday }}</td>
                                    <td class="text-end">{{ nf($_totwatts) }} {{ $_totwatts > 1 ? 'Watts' : 'Watt' }}
                                    </td>
                                </tr>
                            @endforeach
                            @php
                                $totwattsmonth = round(($totwattsday / 1000) * 30, 2);
                                $costmonth = round($totwattsmonth * $cost, 2);
                                $totwattspeak = round($totwattsday * $peakload, 2);
                                $totwattsperhour = round($totwattsday / 24, 2);
                            @endphp
                        </table>
                        <table class="table table-striped table-hover mt-3">
                            <thead>
                                <tr>
                                    <th colspan="6">
                                        <b>Power consumption</b>
                                    </th>
                                </tr>
                            </thead>
                            <tr>
                                <td colspan="3">Total Watts - (Peak Load)</td>
                                <td colspan="3" class="text-end">{{ nf($totwattspeak) }}
                                    {{ $totwattspeak > 1 ? 'Watts' : 'Watt' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">Total Watts per hour </td>
                                <td colspan="3" class="text-end">{{ nf($totwattsperhour) }}
                                    {{ $totwattsperhour > 1 ? 'Watts' : 'Watt' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">Total Watt Hours per Day </td>
                                <td colspan="3" class="text-end">{{ nf($totwattsday) }}
                                    {{ $totwattsday > 1 ? 'Watts' : 'Watt' }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3">Kilowatt Hours Per Month</td>
                                <td colspan="3" class="text-end">
                                    <span class="badge bg-danger">
                                        {{ nf($totwattsmonth) }}
                                        {{ $totwattsmonth > 1 ? 'Watts' : 'Watt' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-striped table-hover mt-3">
                            <thead>
                                <tr>
                                    <th colspan="6">
                                        <b>Cost estimation</b>
                                    </th>
                                </tr>
                            </thead>
                            <tr>
                                <td colspan="3">1 kW</td>
                                <td colspan="3" class="text-end">{{ $cost }} USD</td>
                            </tr>
                            <tr>
                                <td colspan="3">Cost Per Month</td>
                                <td colspan="3" class="text-end">
                                    <span class="badge bg-danger">{{ nf($costmonth) }} USD</span>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-striped table-hover mt-3">
                            @php
                                $totaddon = 0;
                            @endphp
                            @if (count($addons))
                                <thead>
                                    <tr>
                                        <th colspan="3">
                                            <b>Addons</b>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th class="text-end">Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($addons as $k => $v)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ @$v->name }}</td>
                                            <td class="text-end">{{ @$v->cost }} USD</td>
                                        </tr>
                                        @php
                                            $totaddon += (float) @$v->cost;
                                        @endphp
                                    @endforeach
                                </tbody>
                            @endif
                            <tfoot>
                                @if (count($addons))
                                    <tr>
                                        <td>
                                            Total addons
                                        </td>
                                        <td colspan="2" class="text-end">
                                            <span class="badge bg-danger">
                                                {{ nf($totaddon) }} USD
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        Total cost
                                    </td>
                                    <td colspan="2" class="text-end">
                                        <span class="badge bg-danger">
                                            {{ nf($totaddon + $costmonth) }} USD
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a type="button" href="{{ route('admin.contact.edit', $el->id) }}"
                            class="btn btn-outline-secondary" value="{{ $el->id }}">Edit</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit{{ $el->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"> Edit appliances</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form class="was-validated fedit">
                        @csrf
                        <div class="modal-body">
                            <div class="table-responsive">
                                @php
                                    $d = (object) json_decode($el->appliance);
                                    $appliance = (array) @$d->appliance;
                                    $watts = (array) @$d->watts;
                                    $qty = (array) @$d->qty;
                                    $onperday = (array) @$d->onperday;
                                    $totwattspeak = $totwattsday = $totwattsmonth = 0;

                                @endphp
                                @foreach ($appliance as $k => $el)
                                    <div class="d-flex item">
                                        <div class="m-2">
                                            <select class="form-select select2 appliance" name="appliance[]"
                                                value="{{ $el }}" style="min-width: 300px">
                                                @foreach ($appliances as $el2)
                                                    <option value="{{ $el2->name }}" watts="{{ $el2->watts }}"
                                                        @if ($el2->name == $el) selected @endif>
                                                        {{ $el2->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-floating m-2">
                                            <input type="number" min="1" class="form-control quantity"
                                                name="qty[]" id="floatingInput" placeholder="Quantity"
                                                value="{{ @$qty[$k] }}" style="width: 130px">
                                            <label for="floatingInput">Quantity</label>
                                        </div>
                                        <div class="form-floating m-2">
                                            <input type="number" class="form-control watts" id="floatingInput"
                                                value="{{ @$watts[$k] }}" name="watts[]"
                                                placeholder="Watts (Volts x Amps)" style="width: 220px">
                                            <label for="floatingInput">Watts (Volts x Amps)</label>
                                        </div>
                                        <div class="form-floating m-2">
                                            <input type="number" min="0.01" step='0.01' name="onperday[]"
                                                class="form-control hoursperday" id="floatingInput"
                                                placeholder="Hours On per Day" style="width: 200px"
                                                value="{{ @$onperday[$k] }}">
                                            <label for="floatingInput">Hours On per Day</label>
                                        </div>
                                        <div class="form-floating m-2">
                                            <input class="form-control wattsperday" readonly id="floatingInput"
                                                name="wattsperday[]" placeholder="Watt Hours per Day"
                                                style="width: 200px">
                                            <label for="floatingInput">Watt Hours per Day</label>
                                        </div>
                                        <div class="m-2">
                                            <button type="button"
                                                class="btn btn-lg btn-danger remove btn-close text-danger mt-3"
                                                aria-label="Close"></button>
                                        </div>
                                    </div>
                                @endforeach
                                <div id="insertdiv"></div>
                            </div>
                            <button type="button" class="btn btn-link text-danger" id="add-appliance">Add
                                appliance</button>
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
    @endforeach

    <script src="{{ asset('js/b5.js') }}"></script>
    <script src="{{ asset('js/jq.js') }}"></script>
    <script src="{{ asset('js/dt.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/dt.css') }}">

    <script src="{{ asset('js/select2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

    <script>
        $(function() {
            let table = new DataTable('[table]');
            var template = `
            <div class="d-flex item">
                <div class="form-floating m-2">
                    <input required maxlength="100" class="form-control watts" id="floatingInput"
                        name="appliance[]" placeholder="Appliance">
                    <label for="floatingInput">Appliance</label>
                </div>
                <div class="form-floating m-2">
                    <input required type="number" min="0.01" step="0.01" class="form-control watts"
                        id="floatingInput" name="watts[]" placeholder="Watts (Volts x Amps)">
                    <label for="floatingInput">Watts (Volts x Amps)</label>
                </div>
                <div class="m-2">
                    <button type="button" class="btn btn-lg btn-danger remove btn-close text-danger mt-3"
                        aria-label="Close"></button>
                </div>
            </div>`;

            $('#add-appliance').click(function() {
                // var data = $(template);
                // $(data).insertBefore('#insertdiv');
                // var btn = $('.remove', data);
                // var select = $('.select2', data);
                // initRemove(btn);
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

                $.ajax({
                    url: '{{ route('data.appliance.save') }}',
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

            $('#fdel').submit(function() {
                event.preventDefault();
                var form = $(this);
                var rep = $('#rep', form);
                rep.slideUp();
                var data = form.serialize();
                $(':input', form).prop('disabled', true);

                $.ajax({
                    url: '{{ route('data.appliance.delete') }}',
                    type: 'POST',
                    data: data,
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem(
                            "token"),
                    },
                    timeout: 20000,
                    success: function(data) {
                        rep.html(data.message).removeClass().addClass('alert alert-success')
                            .slideDown();
                        form[0].reset();
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
