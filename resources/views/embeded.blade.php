<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Power calculator</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    @include('inc.css')
    <style>
        .table-responsive::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .table-responsive {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .select2-selection.select2-selection--single,
        .select2-selection__arrow {
            height: 3.5rem !important;
        }

        .select2-container--classic .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #32C36C !important;
        }

        .select2-container--classic .select2-selection--single {
            border: 1px solid #32C36C !important;
        }

        .select2-container--classic.select2-container--open .select2-dropdown {
            border: 1px solid #32C36C !important;
        }


        /* remove arrows in number text input */
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body>

    <div class="container-xxl py-5" id="power-calculator">
        <div class="container">
            <div class="text-center mx-auto wow fadeInUp mt-5" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">Use our</h6>
                <h1 class="mb-4">Power calculator</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-12 wow fadeInUp mt-0" data-wow-delay="0.5s">
                    <form class="mt-3 was-validated" id="form">
                        @csrf
                        <div class="table-responsive mt-5 p-3">
                            <div class="mb-5 item-zone" style="max-height: 500px;overflow-y: scroll;">
                                <input type="hidden" name="item" item>
                                <div class="d-flex item">
                                    <div class="m-2">
                                        <select class="form-select select2 appliance" name="appliance[]"
                                            style="min-width: 300px"></select>
                                    </div>
                                    <div class="form-floating m-2">
                                        <input type="number" required min="1" class="form-control quantity"
                                            value="1" name="qty[]" id="floatingInput" placeholder="Quantity"
                                            style="width: 100px">
                                        <label for="floatingInput">Quantity</label>
                                    </div>
                                    <div class="form-floating m-2">
                                        <input type="number" required class="form-control watts" id="floatingInput"
                                            name="watts[]" placeholder="Watts (Volts x Amps)" style="width: 190px">
                                        <label for="floatingInput">Watts (Volts x Amps)</label>
                                    </div>
                                    <div class="form-floating m-2">
                                        <input type="number" required min="0.01" max="24" step='0.01'
                                            value=1 name="onperday[]" class="form-control hoursperday"
                                            id="floatingInput" placeholder="Hours On per Day" style="width: 160px">
                                        <label for="floatingInput">Hours On per Day</label>
                                    </div>
                                    <div class="form-floating m-2">
                                        <input class="form-control wattsperday" readonly id="floatingInput"
                                            name="wattsperday[]" placeholder="Watt Hours per Day"
                                            style="width: 200px">
                                        <label for="floatingInput">Watt Hours per Day</label>
                                    </div>
                                    <div class="m-2 img-div">
                                        @php
                                            $rand = rand();
                                        @endphp
                                        <label for="file-appl-{{ $rand }}">
                                            <img src="" class="img-thumbnail image-appl" alt="image"
                                                style="cursor: pointer;width: 100px; height: 60px; object-fit:cover">
                                        </label>
                                        <input type="file" accept=".jpg,.jpeg,.png,.webp" class="file-appl"
                                            id="file-appl-{{ $rand }}" style="display: none">
                                        <input type="hidden" name='image[]' class="image-data">
                                    </div>
                                    <div class="m-2">
                                        <button type="button"
                                            class="btn btn-lg btn-danger remove btn-close text-danger mt-3"
                                            aria-label="Close"></button>
                                    </div>
                                </div>
                                <div id="insertdiv"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-link text-primary" id="add-appliance">Add appliance</button>
                                <button class="btn btn-link text-primary" id="clearall">Clear all fields</button>
                            </div>
                            <div class="mt-5 d-flex justify-content-between">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addAppliance"
                                    style="text-decoration: underline;">
                                    Appliance not listed ?
                                </a>
                                <div class="d-flex">
                                    <button class="btn btn-outline-secondary m-1 toggle" type="button">Show
                                        result</button>
                                    <button id="print" class="btn btn-outline-secondary m-1" disabled
                                        type="button">Download as PDF</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="uform" class="was-validated">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form div-toggle mt-4" style="display: none">
                                    <h5 class="text-center">Please fill the form</h5>
                                    <div class="form-floating m-2">
                                        <input required class="form-control input-group-sm input-sm"
                                            id="floatingInput" placeholder="" name="name" maxlength="100">
                                        <label for="floatingInput">Your name</label>
                                    </div>
                                    <div class="form-floating m-2">
                                        <input class="form-control input-group-sm input-sm" id="floatingInput"
                                            placeholder="" name="email" type="email" required maxlength="50">
                                        <label for="floatingInput">Your email</label>
                                    </div>
                                    <div class="m-2">
                                        <input class="form-control" required id="phone" placeholder="Your phone"
                                            style="padding: 1rem .75rem !important">
                                    </div>
                                    <div class="form-floating m-2">
                                        <input required class="form-control input-group-sm input-sm"
                                            id="floatingInput" placeholder="" readonly address>
                                        <label for="floatingInput">Your address</label>
                                    </div>
                                    <input type="hidden" name="lat">
                                    <input type="hidden" name="lon">
                                    <input type="hidden" name="address">
                                    <button type="button" class="btn btn-link showmodal">
                                        Pick up on map
                                    </button>
                                    <div id="rep"></div>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-lg btn-primary" type="submit">See result</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="div-result mt-3" style="display: none">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button bg-light" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                    aria-expanded="false" aria-controls="flush-collapseOne">
                                                    <h4>Power consumption</h4>
                                                </button>
                                            </h2>
                                            <div id="flush-collapseOne"
                                                class="accordion-collapse collapse show active">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div
                                                            class="col-md-5 offset-md-7 d-flex justify-content-between text-center">
                                                            <div class="m-1">
                                                                <small>Total Watts(Peak Load)</small>
                                                                <div class="bg-primary text-white p-2 rounded">
                                                                    <b totwattspeak></b>
                                                                </div>
                                                            </div>
                                                            <div class="m-1">
                                                                <small>Total Watts per hour</small>
                                                                <div class="bg-primary text-white p-2 rounded">
                                                                    <b totwattsperhour></b>
                                                                </div>
                                                            </div>
                                                            <div class="m-1">
                                                                <small>Total Watt Hours per Day</small>
                                                                <div class="bg-primary text-white p-2 rounded">
                                                                    <b totwattsday></b>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5 offset-md-7 text-center">
                                                            <div class="">
                                                                <small>Kilowatt Hours Per Month :</small>
                                                                <div class="bg-primary text-white p-2 rounded">
                                                                    <b totwattsmonth></b>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button bg-light collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                    aria-expanded="false" aria-controls="flush-collapseTwo">
                                                    <h4>Cost estimation</h4>
                                                </button>
                                            </h2>
                                            <div id="flush-collapseTwo" class="accordion-collapse collapse show">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-4 offset-md-8 text-center">
                                                            <div class="m-3">
                                                                <small>Cost per kW</small> <br>
                                                                <div class="bg-primary text-white p-2 rounded">
                                                                    <b>1 kW = {{ $cost }} USD</b>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 offset-md-8 text-center">
                                                            <div class="m-3">
                                                                <small>Cost Per Month</small> <br>
                                                                <div class="bg-primary text-white p-2 rounded">
                                                                    <b costmonth></b>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button bg-light collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo2"
                                                    aria-expanded="false" aria-controls="flush-collapseTwo2">
                                                    <h4>Addons</h4>
                                                </button>
                                            </h2>
                                            <div id="flush-collapseTwo2" class="accordion-collapse collapse show">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        @foreach ($addons as $el)
                                                            <div class="col-md-8">
                                                                <h5 class="text-muted">{{ $el->name }}
                                                                </h5>
                                                                <small class="text-primary" showdesc
                                                                    style="cursor: pointer">
                                                                    <i class="fa fa-caret-down"></i>
                                                                    What is the benefit ?</small> <br>
                                                                <i description
                                                                    style="display: none">{{ $el->description }}</i>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <h5>
                                                                    $ {{ $el->cost }}
                                                                </h5>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="d-flex justify-content-end">
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input"
                                                                            name="addons[]"
                                                                            price="{{ $el->cost }}"
                                                                            type="checkbox"
                                                                            id="flexSwitchCheckDefault{{ $el->id }}"
                                                                            value="{{ $el->id }}">
                                                                        <label class="form-check-label"
                                                                            for="flexSwitchCheckDefault{{ $el->id }}"></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="mb-3"></div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 offset-md-7">
                                                            <div class="m-3">
                                                                <table class="table table-bordered mt-3"
                                                                    style="display: none" taddon>
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Total addons price</th>
                                                                            <th class="text-center" totprice></th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                <button style="display: none"
                                                                    class="btn btn-outline-primary btn-update">
                                                                    Add selected Addons
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button bg-light collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseFoura"
                                                    aria-expanded="false" aria-controls="flush-collapseFoura">
                                                    <h4>Total Cost (Cost Per Month + Total addons)</h4>
                                                </button>
                                            </h2>
                                            <div id="flush-collapseFoura" class="accordion-collapse collapse show">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-4 offset-md-8 text-center">
                                                            <div class="m-3">
                                                                <small>Total</small> <br>
                                                                <div class="bg-primary text-white p-2 rounded">
                                                                    <b totgen></b>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"> Where are you ?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <p><strong>Please allow access to your position to get your address</strong></p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                    <div id="map" style="height: 100%; width:100%"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addAppliance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"> Add a new appliance</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="was-validated" id="fappl">
                    <div class="modal-body">
                        <div class="form-floating m-2">
                            <input required maxlength="100" class="form-control watts" id="floatingInput"
                                name="appliance" placeholder="Appliance">
                            <label for="floatingInput">Appliance</label>
                        </div>
                        <div class="form-floating m-2">
                            <input required type="number" min="0.01" step="0.01" class="form-control watts"
                                id="floatingInput" name="watts" placeholder="Watts (Volts x Amps)">
                            <label for="floatingInput">Watts (Volts x Amps)</label>
                        </div>
                        <div class="p-2">
                            <div id="rep"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- @include('inc.footer') --}}

    <script src="{{ asset('js/jq.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    {{-- <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" /> --}}

    <script src="{{ asset('intl/build/js/intlTelInput.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('intl/build/css/intlTelInput.css') }}">
    <style>
        .iti.iti--allow-dropdown.iti--show-flags {
            width: 100% !important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
        integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUN0SL_-boJ4GFG52yxtt1wqqvgUYVjUc"></script> --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"
        integrity="sha512-h9FcoyWjHcOcmEVkxOfTLnmZFWIH0iZhZT1H2TbOq55xssQGEJHEaIm+PgoUaZbRvQTNTluNOEfb1ZRy6D3BOw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"
        integrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/kvn9pG1NpYfqg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-locatecontrol/0.79.0/L.Control.Locate.min.js"
        integrity="sha512-mq6Ep7oDFiumX+lUJle/srDcLqY512R6Yney/E3u3sZZO7T+UgoizxPmAauxoc5qERfMVMcHVITQYf6eKmtjtw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-locatecontrol/0.79.0/L.Control.Locate.css"
        integrity="sha512-6+fQwheLeCW6sKV5liSHZ0rxcrN4TRBwWOhsof+3Iahu9J+WRyF4eBLjL5QshWxja6WiKE1WAtrH0hsRJ3nA8A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script>
        $(function() {
            var template = `
            <div class="d-flex item">
                <div class="m-2">
                    <select class="form-select select2 appliance" name="appliance[]" style="min-width: 300px"></select>
                </div>
                <div class="form-floating m-2">
                    <input required type="number" min="1" class="form-control quantity" value="1"
                        name="qty[]" id="floatingInput" placeholder="Quantity" style="width: 100px">
                    <label for="floatingInput">Quantity</label>
                </div>
                <div class="form-floating m-2">
                    <input required type="number" class="form-control watts" id="floatingInput" name="watts[]"
                        placeholder="Watts (Volts x Amps)" style="width: 190px">
                    <label for="floatingInput">Watts (Volts x Amps)</label>
                </div>
                <div class="form-floating m-2">
                    <input required type="number" min="0.01" step='0.01' max="24" value=1 name="onperday[]"
                        class="form-control hoursperday" id="floatingInput" placeholder="Hours On per Day"
                        style="width: 160px">
                    <label for="floatingInput">Hours On per Day</label>
                </div>
                <div class="form-floating m-2">
                    <input required class="form-control wattsperday" readonly id="floatingInput" name="wattsperday[]"
                        placeholder="Watt Hours per Day" style="width: 200px">
                    <label for="floatingInput">Watt Hours per Day</label>
                </div>
                <div class="m-2 img-div">
                    <label for="file-appl-RANDATA">
                        <img src="" class="img-thumbnail image-appl" alt="image" style="cursor: pointer;width: 100px; height: 60px; object-fit:cover">
                    </label>
                    <input type="file" accept=".jpg,.jpeg,.png,.webp" class="file-appl" id="file-appl-RANDATA"
                        style="display: none">
                    <input type="hidden" name='image[]' class="image-data" >
                </div>
                <div class="m-2">
                    <button type="button" class="btn btn-lg btn-danger remove btn-close text-danger mt-3"
                        aria-label="Close"></button>
                </div>
            </div>
            `;

            var pinput = $('#phone');
            pinput.mask('0000000000000');

            var intlInput = intlTelInput(pinput[0], {
                initialCountry: "auto",
                separateDialCode: true,
                preferredCountries: ['CD'],
                geoIpLookup: function(callback) {
                    fetch("https://ipapi.co/json")
                        .then(function(res) {
                            return res.json();
                        })
                        .then(function(data) {
                            callback(data.country_code);
                        })
                        .catch(function() {
                            callback("CD");
                        });
                }
            });

            ITEMS = {!! $items !!};
            var data = JSON.stringify(ITEMS);
            localStorage.setItem('items', data);
            TOGGLE = false;

            COST = '{{ $cost }}';
            COST = Number(COST);
            COST = COST ? COST : 0;
            PEAKLOAD = '{{ $peakload }}';
            PEAKLOAD = Number(PEAKLOAD);
            PEAKLOAD = PEAKLOAD ? PEAKLOAD : 0;

            function getItem(all = '') {
                var items = localStorage.getItem('items');
                if (!items) {
                    items = '[]';
                }
                var tab = JSON.parse(items);
                if (all == 'all') {
                    var items = localStorage.getItem('user_items');
                    if (items) {
                        items = JSON.parse(items);
                        tab = tab.concat(items);
                    }
                }
                return tab;
            }

            function setItem(appliance, watts) {
                var items = localStorage.getItem('user_items');
                if (!items) {
                    items = '[]';
                }
                items = JSON.parse(items);

                var id = getItem('all').length + 1
                items.push({
                    id: id,
                    item: appliance,
                    watts: watts
                });
                localStorage.setItem('user_items', JSON.stringify(items));
                initSelect();

                var rand = Math.random().toString().split('0.').join('');
                var _template = template;
                _template = _template.split('RANDATA').join(rand);
                var data = $(_template);
                insertAppliance(data);
                $('select', data).val(appliance).change();
                not_listed();
            }

            function calculate(usedefault = true) {
                var el = $('.item');

                var totwattsday = 0;
                var totwattspeak = 0;
                var totwattsmonth = 0;
                el.each(function() {
                    var e = $(this);
                    var select = $('.appliance', e);
                    var watt = Number($(':selected', select).attr('watts'));
                    appliance_watt = watt ? watt : 0;

                    var qte = Number($('.quantity', e).val());
                    qte = qte ? qte : 0;

                    var watts = Number(appliance_watt);
                    if (usedefault) {
                        $('.watts', e).val(watts);
                    } else {
                        watts = Number($('.watts', e).val());
                    }
                    watts = watts ? watts : 0;

                    var hoursperday = Number($('.hoursperday', e).val());
                    hoursperday = hoursperday ? hoursperday : 0;

                    var v = qte * watts * hoursperday;
                    totwattsday += v;
                    v = v.toFixed(2)
                    $('.wattsperday', e).val(v);
                });

                totwattsmonth = (totwattsday / 1000) * 30;
                costmonth = totwattsmonth * COST;
                totwattspeak = totwattsday * PEAKLOAD;
                totwattsperhour = totwattsday / 24;

                var totaddon = $('[totprice]').html();
                totaddon = Number(totaddon.split(' ')[0]);
                totaddon = totaddon ? totaddon : 0;
                var totgen = totaddon + costmonth;

                if (costmonth > 0) {
                    $('.btn-update').attr('disabled', false);
                } else {
                    $('.btn-update').attr('disabled', true);
                }

                $('[totwattspeak]').html((Number(totwattspeak.toFixed(2))).toLocaleString('fr-FR').toString().split(
                    ',').join('.'));
                $('[totwattsperhour]').html((Number(totwattsperhour.toFixed(2))).toLocaleString('fr-FR').toString()
                    .split(',').join('.'));
                $('[totwattsday]').html((Number(totwattsday.toFixed(2))).toLocaleString('fr-FR').toString().split(
                    ',').join('.'));
                $('[totwattsmonth]').html((Number(totwattsmonth.toFixed(2))).toLocaleString('fr-FR').toString()
                    .split(',').join('.') + ' kWh/mo');
                $('[costmonth]').html((Number(costmonth.toFixed(2))).toLocaleString('fr-FR').toString().split(',')
                    .join('.') + ' USD');
                $('[totgen]').html((Number(totgen.toFixed(2))).toLocaleString('fr-FR').toString().split(',')
                    .join('.') + ' USD');

            }

            var form = $('#form');
            form.submit(function() {
                event.preventDefault();
            });

            function initChange() {
                // off('change')
                $('select, input', form).each(function(e) {
                    var el = $(this);
                    if (!el.data('change')) {
                        el.change(function(e) {

                            if (TOGGLE) {
                                $('.div-toggle').slideDown('slow');
                                $('.div-result').slideUp('slow');
                                $('#print').attr('disabled', true);
                            }

                            var usedefault = e.target.classList.contains('appliance');
                            if (e.target.classList.contains('hoursperday')) {
                                var v = Number(el.val());
                                v = v ? v : 0;
                                if (v > 24) {
                                    el.val(24);
                                    alert('Max value for hours is 24');
                                }
                            }
                            calculate(usedefault);
                        })
                    }
                });
            }

            initChange();

            function scrollTo(element) {
                element.stop();
                element.animate({
                    scrollTop: element.prop("scrollHeight")
                }, 1000);
            }

            function insertAppliance(data) {
                $(data).insertBefore('#insertdiv');
                var btn = $('.remove', data);
                var select = $('.select2', data);
                initRemove(btn);
                initSelect();
                // initSelect(select);
                initChange();
                calculate();
                preview();
                initFileChange();
                const element = $('.item-zone');
                scrollTo(element);
            }

            $('#add-appliance').click(function() {
                var rand = Math.random().toString().split('0.').join('');
                var _template = template;
                _template = _template.split('RANDATA').join(rand);
                var data = $(_template);
                insertAppliance(data);
            });
            $('#clearall').click(function() {
                form[0].reset();
                $(".select2").val(null).trigger("change");
            })

            function initRemove(item = null) {
                if (item == null) {
                    item = $('.remove');
                }
                item.click(function() {
                    item.closest('.item').remove();
                    calculate();
                });
            }

            function initSelect(item = null) {
                if (item == null) {
                    item = $('.select2');
                }

                var str = '<option value="">Select appliance</option>';
                var items = getItem('all');
                for (i in items) {
                    var el = items[i];
                    str += `<option watts="${el.watts}" value="${el.item}" >${el.item}</option>`;
                }

                $(item).each(function(i, e) {
                    var odlv = e.value;
                    var el = $(e);
                    el.html(str);
                    if ($(e).data('select2')) {
                        $(e).select2('destroy');
                    }
                    el.select2({
                        theme: 'classic'
                    });
                    if (odlv) {
                        el.val(odlv).change();
                    }
                });
            }

            $('[showdesc]').click(function() {
                var el = $(this);
                var i = el.find('i');
                var desc = el.closest('div').find('[description]');
                if (desc.is(':visible')) {
                    i.removeClass('fa-caret-up').addClass('fa-caret-down');
                    desc.slideUp()
                } else {
                    i.removeClass('fa-caret-down').addClass('fa-caret-up');
                    desc.slideDown()
                }

            })

            var addonSel = $('[name="addons[]"]');

            function addonTot() {
                var tot = 0;
                addonSel.each(function(i, e) {
                    if (e.checked) {
                        var p = Number($(e).attr('price'));
                        tot += p;
                    }
                });
                if (tot > 0) {
                    $('[totprice]').html(tot + ' USD');
                    $('[taddon]').slideDown();
                    $('.btn-update').slideDown();

                } else {
                    $('[totprice]').html('0 USD');
                    $('[taddon]').slideUp();
                    $('.btn-update').slideUp();
                }
            }

            addonSel.change(function() {
                addonTot();
                calculate();
            })

            addonTot();
            initRemove();
            initSelect();
            calculate();

            $('#fappl').submit(function() {
                event.preventDefault();
                var form = $(this);
                var rep = $('#rep', form);
                rep.slideUp();
                var appl = $('[name=appliance]', form).val().trim();
                var watts = Number($('[name=watts]', form).val());

                if (!watts || watts == 0) {
                    rep.removeClass().addClass('alert alert-danger').html("Invalid watts value")
                        .slideDown();
                    return
                }
                if (appl.length == 0) {
                    rep.removeClass().addClass('alert alert-danger').html("Invalid appliance name")
                        .slideDown();
                    return
                }

                var items = getItem('all');
                for (i in items) {
                    var el = items[i];
                    if (el.item == appl) {
                        var m = `Appliance "${appl}" already exists`;
                        rep.removeClass().addClass('alert alert-danger').html(m)
                            .slideDown();
                        return
                    }
                }
                setItem(appl, watts);
                rep.removeClass().addClass('alert alert-success').html(
                        "Your appliance has been added successfully")
                    .slideDown();
                setTimeout(() => {
                    rep.slideUp();
                }, 5000);
                form[0].reset();
            })

            // map
            var map;
            var faisalabad = {
                lat: -11.6391936,
                lng: 27.4726912
            };

            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: faisalabad
                });
                myMarker = new google.maps.Marker({
                    map: map,
                    position: faisalabad,
                    draggable: true,
                    title: "Drag me"
                });
                myMarker.addListener('dragend', function(event) {
                    getAddress(event.latLng.lat(), event.latLng.lng());
                });
                addYourLocationButton(map, myMarker);
                getCurrentPos(myMarker);
            }

            function addYourLocationButton(map, marker) {
                var controlDiv = document.createElement('div');
                var firstChild = document.createElement('button');
                firstChild.style.backgroundColor = '#fff';
                firstChild.style.border = 'none';
                firstChild.style.outline = 'none';
                firstChild.style.width = '40px';
                firstChild.style.height = '40px';
                firstChild.style.borderRadius = '2px';
                firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
                firstChild.style.cursor = 'pointer';
                firstChild.style.marginRight = '10px';
                firstChild.style.padding = '0px';
                firstChild.title = 'Your Location';
                controlDiv.appendChild(firstChild);

                var secondChild = document.createElement('div');
                secondChild.style.margin = '10px';
                secondChild.style.width = '18px';
                secondChild.style.height = '18px';
                secondChild.style.backgroundImage =
                    'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-1x.png)';
                secondChild.style.backgroundSize = '200px 20px';
                secondChild.style.backgroundPosition = '0px 0px';
                secondChild.style.backgroundRepeat = 'no-repeat';
                secondChild.id = 'you_location_img';
                firstChild.appendChild(secondChild);

                google.maps.event.addListener(map, 'dragend', function() {
                    $('#you_location_img').css('background-position', '0px 0px');
                });

                firstChild.addEventListener('click', function() {
                    event.preventDefault();
                    try {
                        clearInterval(animationInterval);
                    } catch (error) {}
                    var imgX = '0';
                    var animationInterval = setInterval(function() {
                        if (imgX == '-18') {
                            imgX = '0';
                        } else {
                            imgX = '-18';
                        }
                        $('#you_location_img').css('background-position', imgX + 'px 0px');
                    }, 500);

                    if (navigator.geolocation) {
                        getCurrentPos(myMarker, animationInterval);
                    } else {
                        clearInterval(animationInterval);
                        $('#you_location_img').css('background-position', '0px 0px');
                    }
                });
                controlDiv.index = 1;
                map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
            }

            function getAddress(lat, lng, animationInterval = null) {
                $.ajax({
                    url: "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyAUN0SL_-boJ4GFG52yxtt1wqqvgUYVjUc&latlng=" +
                        lat + "," +
                        lng,
                    success: function(data) {
                        var result = data.results;
                        if (result.length > 0) {
                            var addr = result[0].formatted_address;
                            $('[name="address"]').val(addr);
                            $('.modal').modal('hide');
                        } else {
                            alert(
                                'Something went wrong with location. Please try again'
                            );
                        }
                        clearInterval(animationInterval);
                        $('#you_location_img').css('background-position',
                            '0px 0px');

                    },
                    error: function() {
                        alert(
                            'Something went wrong with location. Please try again'
                        );
                        clearInterval(animationInterval);
                        $('#you_location_img').css('background-position',
                            '0px 0px');
                    }
                }, );
            }

            function getCurrentPos(marker, animationInterval) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latlng = new google.maps.LatLng(position.coords.latitude, position
                        .coords.longitude);
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;
                    $('[name=lat]').val(lat);
                    $('[name=lon]').val(lon);
                    marker.setPosition(latlng);
                    map.setCenter(latlng);
                    getAddress(lat, lon, animationInterval);
                }, function(error) {
                    alert(error.message);
                    clearInterval(animationInterval);
                });
            }

            HIDEMODAL = false;

            function initMap2() {
                var a = [-11.6391936, 27.4726912];
                var map = L.map('map').setView(a, 13);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                lc = L.control.locate({
                    flyTo: true,
                    locateOptions: {
                        enableHighAccuracy: true
                    }
                }).addTo(map);

                var marker = L.marker({
                    lat: a[0],
                    lng: a[1]
                }, {
                    draggable: 'true'
                });

                marker.on('dragend', function(event) {
                    var position = marker.getLatLng();
                    marker.setLatLng(position, {
                        draggable: 'true'
                    }).bindPopup(position).update();
                    var lat = position.lat;
                    var lon = position.lng;
                    $('[name=lat]').val(lat);
                    $('[name=lon]').val(lon);
                    HIDEMODAL = true;
                    getAdd(position.lat, position.lng);
                })

                marker.addTo(map);
                map.on('locationfound', function(e) {
                    var latl = (e.latlng);
                    var lat = latl.lat;
                    var lon = latl.lng;

                    $('[name=lat]').val(lat);
                    $('[name=lon]').val(lon);

                    var radius = e.accuracy;
                    // L.marker(e.latlng, {
                    //         draggable: 'true'
                    //     }).addTo(map)
                    //     .bindPopup("You are within " + radius + " meters from this point").openPopup();
                    var position = e.latlng;
                    marker.setLatLng(position, {
                        draggable: 'true'
                    })
                    marker.bindPopup("You are within " + radius +
                            " meters from this point, Drag this marker to ajust your position!").update()
                        .openPopup();
                    getAdd(lat, lon);
                    setTimeout(() => {
                        HIDEMODAL = true;
                    }, 3000);
                });
                lc.start();
            }

            function getAdd(lat, lon) {
                $.ajax({
                    url: "https://nominatim.openstreetmap.org/reverse?lat=" + lat + "&lon=" +
                        lon + "&format=json",
                    success: function(data) {
                        var addr = data.display_name
                        var o = data.address
                        o.address = addr;
                        if (addr) {
                            $('[address]').val(addr);
                            $('[name="address"]').val(JSON.stringify(o));
                            if (HIDEMODAL) {
                                $('.modal').modal('hide');
                            }
                        } else {
                            alert(
                                'Something went wrong. Please try again'
                            );
                        }
                    },
                    error: function() {
                        alert(
                            'Something went wrong with location. Please try again'
                        );
                    }
                });
            }

            $('.showmodal').click(function() {
                $('#staticBackdrop').modal('show');
                setTimeout(() => {
                    try {
                        initMap2();
                    } catch (error) {}
                }, 900);
            })

            try {
                // initMap();
            } catch (error) {
                console.log(error);
            }

            var uform = $('#uform');


            function sendRes(lastId = null) {
                var countryData = intlInput.getSelectedCountryData();
                var dialCode = $('.iti__selected-dial-code').html();
                var n = $('#phone').val();
                var rep = $('#rep', uform);
                rep.stop().slideUp();
                if (dialCode.length == 0) {
                    rep.html("Please select your country before the number field.").removeClass().addClass(
                            'alert alert-danger')
                        .slideDown();
                    return false;
                }
                var number = '' + dialCode + n;

                $(':input', uform).prop('disabled', false);
                var data = uform.serialize();
                data += '&phone=' + encodeURIComponent(number);
                var _data = form.serialize();
                $(':input', uform).prop('disabled', true);
                if (_data.length) {
                    data += '&' + _data;
                }

                if (lastId) {
                    data += '&item=' + lastId;
                }

                $.ajax({
                    method: 'POST',
                    url: '{{ route('new_request') }}',
                    data: data,
                    timeout: 45000,
                    success: function(data) {
                        if (data.success) {
                            $('.btn-update').val(data.item);
                            $('[item]').val(data.item);
                            if (lastId) {
                                return
                            }
                            rep.html(data.message).removeClass().addClass('alert alert-success')
                                .slideDown();
                            $('#print').attr('disabled', false);
                            setTimeout(() => {
                                $('.div-toggle').slideUp('slow');
                                var el = $('.div-result')
                                el.slideDown('slow');
                                setTimeout(() => {
                                    window.scrollTo({
                                        top: el[0].offsetTop - 130,
                                        behavior: 'smooth'
                                    });
                                }, 500);
                                TOGGLE = true;
                            }, 3000);

                        } else {
                            if (lastId) {
                                return
                            }
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
                    var b = $('.btn-update').html('<i class="fa fa-check-circle fa-2x"></i>');
                    setTimeout(() => {
                        b.attr('disabled', false).html('Add selected Addons');
                    }, 1000);
                    $(':input', uform).not('[name=address]').prop('disabled', false);

                    if (lastId) {
                        return
                    }
                    setTimeout(() => {
                        rep.slideUp();
                    }, 10000);
                })
            }
            uform.on('submit', function() {
                event.preventDefault();
                sendRes();
            })

            $('.btn-update').click(function() {
                event.preventDefault();
                var id = this.value;
                $(this).attr('disabled', true);
                $(this).html('<i class="spinner-border spinner-border-sm"></i>')
                sendRes(id);
            })

            $('#print').click(function() {
                var btn = $(this);
                var data = $(':input', form).serialize();
                var _data = uform.serialize();
                data += "&" + _data

                var link = document.createElement("a");
                var href = '{{ route('makepdf') }}';
                btn.attr('disabled', true);
                fetch(href, {
                        method: 'POST',
                        body: data,
                        headers: {
                            'Content-type': 'application/x-www-form-urlencoded'
                        }
                    })
                    .then(resp => resp.blob())
                    .then(blob => {
                        btn.attr('disabled', false);
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = 'PowerCalculatorResult.pdf';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                    })
                    .catch(function(rep) {
                        btn.attr('disabled', false);
                        alert('Download error');
                    });
            })

            function not_listed() {
                var items = localStorage.getItem('user_items');
                if (!items) {
                    items = '[]';
                }
                $.ajax({
                    method: 'POST',
                    url: '{{ route('not_listed') }}',
                    data: {
                        data: items
                    },
                })
            }
            not_listed();

            var icon =
                'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZAAAAGQCAYAAACAvzbMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAACkVJREFUeNrs3W9PGkkAwGFSBDnJwh629+LeaOL3/0gm+vJyd01bU1Pt6d1MuySEU4FlZ/8+T0L6olFwXPbHwDj7bgQAJbwzBAAICAACAoCAACAgACAgAAgIAAICgIAAICAAICAACAgAAgKAgAAgIAAgIAAICAACAoCAACAgACAgAAgIAAICgIAAICAAICAACAgAAgKAgAAgIAAgIAAICAACAoCAACAgACAgAAgIAAICgIAAgIAAICAACAgAAgKAgACAgAAgIAAICAACAoCAAICAACAgAAgIAAICgIAAgIAAICAACAgAAgKAgACAgAAgIAAICAACAoCAAICAACAgAAgIAAICgIAAgIAAICAACAgAAgKAgACAgAAgIAAICAACAgACAoCAACAgAAgIAAICAAICgIAAICAACAgAAgIAAgKAgAAgIAAICAACAgACAoCAACAgAAgIAAICAAICgIAAICAACAgAAgIAAgKAgAAgIAAICAACAgACAoCAACAgAAgIAAgIAAICgIAA0HYnhuA4VxeXp+GfqZGAznm6vr25NwwC0qQYj8wwQOc8hpuAHMFbWAAICAACAoCAACAgACAgAAgIAAICgIAAICAAICAACAgAAgKAgAAgIAAgIAAICAACAoCAACAgAPCWE0PQWk/FDfh5rvKCV0DY0/317c2dYYDR6Ori8n34Z2ok2kXRARAQAAQEAAGhpLEhAASEMixwAAQEAAEBAAEBQEAAEBAABAQAAQEAAQFAQAAQEAA6xHYZ0DNXF5dxH7VZuJ2Ofu6pNtn47/WFyh7C7dv17c13I4aAgHCchX/mW8HYNi5u8eJMWfiaGJP7cPsaYvJsFBEQGFY4YjDyHeF4KyhZDE/4Pp9CRL4ZUQQEhjPryCv4VvHz0FX4fnE28sVshH0PGqCb8cgrisemGKTz8L2dGxAQ6Gk8suJkn0J8K+zcKCMg0L94xBVWWeK7mYT7WRptBAT6E4/4nM1rurv4wfqpUUdAoB8WNT9vc0OOgED3Zx9xye1ZzXc7LlZ6gYBAh80aul8BQUCg45o6kU8t60VAoKOKE/ikwYcw81tAQKCbJg3f/9ivAAGBbmp62yHLeREQ8FwFByUAAgKAgAApPDZ8/65ciICQlr2Tkvln4PePgNDzeMS/FTgvrpBHha5vb9bXMh/qDAgBoefW23+LSBoPDd3vUwiYt7AQEJLNPuI2G+ON40pEqve1ofu9N/QICKniEY+j7IVjS0QqVMwC6n4r6bnBcCEgDMB89PJWF+uIOM6q87nuWU8I17NhR0BINfuY7zjGRKTaWchdTXf3PdzfnVFHQEgl2+M4mohIpRGJJ/XUb2XFWcdHo42AkGr2Md4x+xCRdOLJPdXKqBiPv4ulwyAgJJt9HOJHRAxbJbOQHyf5BDORpyIelu0iICSbfcQYlLlC3iR8bW4Eq4lIuP01qu4zkW/h9qd4ICCktjzia89EpNKQxID8MSr/9xqPxazjoxVXHOLEEFBi9hH3u5oe+W1iROLJ75MRrSQi8a2nT2FMY0ziljK/7Pgdrf+m5N6MAwGhTllF3ydG5N9wAvtsSCsNydfitl7osPk8fxYMBISmZh9nFcw+Ns3D94x/b2C7jHRBsZqKJHwGQlOzj015ESZAQOjp7OO1LUtEBAQEXo3Hu0SzDxEBAaHn5jUdLws7+IKA0K/Zx7zGY9I28CAg9MSi5mNFREBA6MHsI35oftbQsdmpiMSZWrhljhoEBH7KGj4+Vx3awTe+zZeFx/versMICEOffZw2NPvYNB51YBv4ra3tpyNb1yMgmH20QheuJbJ9Ya34mH/zOQ4CwlBnH9MWPaTWRuSNmdr6c5xTRxQCwpC0cbv1GJFVCx/XYsdz7NwfSCIgDGX2EU9245Y+vGmbriVSjNU+b1PlVmghIAxB2090rbggVfF22uKQcXUhLQSEPs8+shbPPtoWkTLbu5xZ5ouA0Md41LllSVURyRoaq/ERMzXLfBEQemfewWMia+gD6uWRX2+ZLwJCb2Yfx7yiblqt28AXy3JnFT3/4kxk5ghEQOiyrq8QqjMiecXPwZVlvggIXZ599OEElqd+NZ/wqozxsS8djQgIXfNrj36WPNXnCjVclXFumS8CQpdmH23bsqSKYzrVNvBZDc+ZuLLsgxVaCAhdkPX0uK40IsX3qmuJ83rfr7HDEwGhrbOPWc9mHykjUvfnE/Fxf7DMFwGhrZYDOL7zY98OajC0lvkiILRy9tHmDROrfiV/7F99Lxt+jlrmi4DQmngcugngYCPSor3BLPNFQGiF+QB/9wdHpIV7g1nmi4DQ+OxjPtAfP0bkkBPwooXPEct8ERAakw389z7b51X8G5epbdNsygotBITaZh/jAc8+tl/F74pI2/8+RkQQEGqffbAjIsWKp2lHnr+ut46AkHz2EV+pOtH8PyLzrXFKvd9ViudwLiIICClZAvrKuGydfFPttptaboUWdTsxBIOYffRtw8QUJ9/478Oo258RnRU/x5fr25tnv1bMQKjCwhDsjki4rXrwnIizKddbR0CoZPYRTyhW6uxn0qOfwwotBISjWXk13BiKCAJC6dlHVz8Qprrn9wcrtBAQDo1H15ajkk6+vVwZBIS3zP1+2bC0zBcBYd/Zh1ecbIvLfFdWaCEgvGXhd8sr4tUNLfNFQHhx9hE/NPehKW9xvXUEhBf54Jx9jIuZyKmhQEBYsz0Nhzz/7eaLgACl5cW130FAgINllvkiIEBZrreOgAClrffQsgUOAgKUiohlvggIUPrcEGciM0OBgABlzg8ry3wREKAs11vnRf7oDNiH661jBgKUj8jIRowICFCSFVoICFDaeiNGEREQgFLnDtdbdxAAlGYjRgEBKM1GjANlGW//3Ifbg2GgbvHiVNe3N449AaGrwhP43igAdfAWFgACAoCAACAgAAgIAAgIAAICgIAAICAACAgACAgAAgJAU2ym2F7Tq4vL3w0DYAYCgIAAgIAAICAACAgAAgKAgACAgAAgIAAICAACAoCAAICAACAgAAgIAAICgIAAgIAAICAACAgAXXRiCI72GG53hgE658kQAEADvIUFgIAAICAACAgAAgIAAgKAgAAgIAAICAACAgACAoCAACAgAAgIAAICAAICgIAAICAACAgAAgIAAgKAgAAgIAAICAACAgACAoCAACAgAAgIAAICAAICgIAAICAACAgAAgIAAgKAgAAgIAAICAAICAACAoCAACAgAAgIAAgIAAICgIAAICAACAgACAgAAgKAgAAgIAAICAAICAACAoCAACAgAAgIAAgIAAICgIAAICAACAgACAgAAgKAgAAgIAAICAAICAACAoCAACAgAAiIIQBAQAAQEAAEBAABAQABAUBAABAQAAQEAAEBAAEBQEAAEBAABAQAAQEAAQFAQAAQEAAEBAABAQABAUBAABAQAAQEAAEBAAEBQEAAEBAABAQAAQEAAQFAQAAQEAAEBAABAQABAUBAABAQAAQEAAQEgJL+E2AAvv8YFBUVTjoAAAAASUVORK5CYII=';

            function preview() {
                $('.image-appl[src=""]').each(function() {
                    var e = $(this);
                    e.attr('src', icon);
                    e.closest('.img-div').find('.image-data').val(icon);
                })
            }

            preview();

            function initFileChange() {
                $('.file-appl').off('change').change(function(e) {
                    if (!window.FileReader) {
                        alert("The file API isn't supported on this browser yet.");
                        return;
                    }
                    var input = $(this);
                    var files = e.target.files;
                    var done = function(url) {
                        var image = input.closest('.img-div').find('img');
                        image.attr('src', url);
                        input.closest('.img-div').find('.image-data').val(url);
                    };
                    var reader;
                    var file;
                    var url;
                    if (files && files.length > 0) {
                        file = files[0];

                        var pattern = /image-*/;
                        if (!file.type.match(pattern)) {
                            alert('Only images are allowed');
                            return;
                        }

                        var size = file.size;
                        if (!size) {
                            alert('Could not get file size.');
                            return;
                        }

                        var max = 1024 * 300;
                        var fsize = (size / 1024).toFixed(2) + " Kb";

                        if (size > max) {
                            alert("The Max file size is 300Kb, your file is " + fsize);
                            return;
                        }

                        reader = new FileReader();
                        reader.onload = function(e) {
                            done(reader.result);
                        };
                        reader.readAsDataURL(file);
                    }
                })
            }

            initFileChange();

            $('.toggle').click(function() {
                var el = $('.div-toggle')
                el.slideToggle('slow');
                setTimeout(() => {
                    if (el.is(':visible')) {
                        window.scrollTo({
                            top: el[0].offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                }, 900);

            })
        })
    </script>
    @if (session('_message'))
        <script>
            $(function() {
                alert("{{ session('_message') }}")
            })
        </script>
    @endif

</body>

</html>
