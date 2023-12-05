<html lang="lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Power calculator</title>
    <link rel="stylesheet" href="{{ asset('css/b5.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.b5.css') }}">
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

        .select2-selection.select2-selection--single,
        .select2-selection__arrow {
            height: 3.5rem !important;
        }

        .accordion-button:focus {
            box-shadow: none;
        }
    </style>
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
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-12 pb-5">
                <h1 class="text-center mt-5">Power calculator</h1>
                <form class="mt-3" id="form">
                    <div class="table-responsive mt-5">
                        <div class="d-flex item">
                            <div class="m-2">
                                <select class="form-select select2 appliance" name="appliance[]"
                                    style="min-width: 300px"></select>
                            </div>
                            <div class="form-floating m-2">
                                <input type="number" min="1" class="form-control quantity" value="1"
                                    name="qty[]" id="floatingInput" placeholder="Quantity" style="width: 130px">
                                <label for="floatingInput">Quantity</label>
                            </div>
                            <div class="form-floating m-2">
                                <input type="number" class="form-control watts" id="floatingInput" name="watts[]"
                                    placeholder="Watts (Volts x Amps)" style="width: 220px">
                                <label for="floatingInput">Watts (Volts x Amps)</label>
                            </div>
                            <div class="form-floating m-2">
                                <input type="number" min="0.01" step='0.01' value=1 name="onperday[]"
                                    class="form-control hoursperday" id="floatingInput" placeholder="Hours On per Day"
                                    style="width: 200px">
                                <label for="floatingInput">Hours On per Day</label>
                            </div>
                            <div class="form-floating m-2">
                                <input class="form-control wattsperday" readonly id="floatingInput" name="wattsperday[]"
                                    placeholder="Watt Hours per Day" style="width: 200px">
                                <label for="floatingInput">Watt Hours per Day</label>
                            </div>
                            <div class="m-2">
                                <button type="button" class="btn btn-lg btn-danger remove btn-close text-danger mt-3"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        <div id="insertdiv"></div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-link text-danger" id="add-appliance">Add appliance</button>
                            <button class="btn btn-link text-danger" id="clearall">Clear all fields</button>
                        </div>
                        <div class="mt-5 d-flex justify-content-between">
                            <button class="btn btn-link text-muted" data-bs-toggle="modal"
                                data-bs-target="#addAppliance"> Appliance not listed ?</button>
                            <button id="print" class="btn btn-outline-secondary" disabled
                                type="button">Print</button>
                        </div>
                </form>
            </div>
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button bg-light" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false"
                            aria-controls="flush-collapseOne">
                            <h4>Power consumption</h4>
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse show active">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-4 offset-md-8 d-flex text-center">
                                    <div class="m-3">
                                        <small>Total Watts - (Peak Load)</small> <br>
                                        <div class="bg-danger text-white p-2 rounded">
                                            <b totwattspeak></b>
                                        </div>
                                    </div>
                                    <div class="m-3">
                                        <small>Total Watt Hours per Day :</small> <br>
                                        <div class="bg-danger text-white p-2 rounded">
                                            <b totwattsday></b>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 offset-md-8 text-center">
                                    <div class="m-3">
                                        <small>Kilowatt Hours Per Month :</small> <br>
                                        <div class="bg-danger text-white p-2 rounded">
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
                        <button class="accordion-button bg-light collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseTwo" aria-expanded="false"
                            aria-controls="flush-collapseTwo">
                            <h4>Cost estimation</h4>
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-4 offset-md-8 text-center">
                                    <div class="m-3">
                                        <small>Cost</small> <br>
                                        <div class="bg-danger text-white p-2 rounded">
                                            <b>1 kW = {{ $cost }} USD</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 offset-md-8 text-center">
                                    <div class="m-3">
                                        <small>Cost Per Month :</small> <br>
                                        <div class="bg-danger text-white p-2 rounded">
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
                        <button class="accordion-button bg-light collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseThree" aria-expanded="false"
                            aria-controls="flush-collapseThree">
                            <h4>Send result</h4>
                        </button>
                    </h2>
                    <div id="flush-collapseThree" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form">
                                        <form id="uform" class="was-validated">
                                            <div class="form-floating m-2">
                                                <input required class="form-control quantity" id="floatingInput"
                                                    placeholder="" name="name" maxlength="100">
                                                <label for="floatingInput">Your name</label>
                                            </div>
                                            <div class="form-floating m-2">
                                                <input class="form-control quantity" id="floatingInput"
                                                    placeholder="" name="email" type="email" maxlength="50">
                                                <label for="floatingInput">Your email</label>
                                            </div>
                                            <div class="form-floating m-2">
                                                <input class="form-control quantity" id="floatingInput"
                                                    type="phone" placeholder="" name="phone" maxlength="50">
                                                <label for="floatingInput">Your phone</label>
                                            </div>
                                            <div class="form-floating m-2">
                                                <input required class="form-control quantity" id="floatingInput"
                                                    placeholder="" readonly disabled name="address">
                                                <label for="floatingInput">Your address</label>
                                            </div>
                                            <input type="hidden" name="lat">
                                            <input type="hidden" name="lon">
                                            <button type="button" class="btn btn-link showmodal">
                                                Pick up on map
                                            </button>
                                            <div id="rep"></div>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-lg btn-danger" type="submit">Send</button>
                                            </div>
                                        </form>
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
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
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
                        <button type="submit" class="btn btn-danger">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div style="display: none">
        <div class="printzone"></div>
    </div>

    <script src="{{ asset('js/b5.js') }}"></script>
    <script src="{{ asset('js/jq.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUN0SL_-boJ4GFG52yxtt1wqqvgUYVjUc"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js"></script>

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
                    <input type="number" min="1" class="form-control quantity" value="1"
                        name="qty[]" id="floatingInput" placeholder="Quantity" style="width: 130px">
                    <label for="floatingInput">Quantity</label>
                </div>
                <div class="form-floating m-2">
                    <input type="number" class="form-control watts" id="floatingInput" name="watts[]"
                        placeholder="Watts (Volts x Amps)" style="width: 220px">
                    <label for="floatingInput">Watts (Volts x Amps)</label>
                </div>
                <div class="form-floating m-2">
                    <input type="number" min="0.01" step='0.01' value=1 name="onperday[]"
                        class="form-control hoursperday" id="floatingInput" placeholder="Hours On per Day"
                        style="width: 200px">
                    <label for="floatingInput">Hours On per Day</label>
                </div>
                <div class="form-floating m-2">
                    <input class="form-control wattsperday" readonly id="floatingInput" name="wattsperday[]"
                        placeholder="Watt Hours per Day" style="width: 200px">
                    <label for="floatingInput">Watt Hours per Day</label>
                </div>
                <div class="m-2">
                    <button type="button" class="btn btn-lg btn-danger remove btn-close text-danger mt-3"
                        aria-label="Close"></button>
                </div>
            </div>
            `;

            ITEMS = {!! $items !!};
            var data = JSON.stringify(ITEMS);
            localStorage.setItem('items', data);

            COST = '{{ $cost }}';
            COST = Number(COST);
            COST = COST ? COST : 0.05;

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
                    var tw = v / hoursperday;
                    totwattspeak += tw;
                    totwattsday += v;
                    v = v.toFixed(2)
                    $('.wattsperday', e).val(v);
                });

                totwattsmonth = (totwattsday / 1000) * 30;
                costmonth = totwattsmonth * COST;
                if (costmonth > 0) {
                    $('#print').attr('disabled', false);
                } else {
                    $('#print').attr('disabled', true);
                }

                $('[totwattspeak]').html(totwattspeak.toFixed(2));
                $('[totwattsday]').html(totwattsday.toFixed(2));
                $('[totwattsmonth]').html(totwattsmonth.toFixed(2) + ' kWh/mo');
                $('[costmonth]').html(costmonth.toFixed(2) + ' USD');

            }

            var form = $('#form');
            form.submit(function() {
                event.preventDefault();
            });

            function initChange() {
                // off('change')
                // $('select, input', form).change(function(e) {
                //     // if ($(e).data('change')) {
                //     //     console.log('IF');
                //     //     return false;
                //     // }
                //     var usedefault = e.target.classList.contains('appliance');
                //     calculate(usedefault);
                //     console.log('CALC', $(this).data('change') );
                // });

                $('select, input', form).each(function(e) {
                    var el = $(this);
                    if (!el.data('change')) {
                        el.change(function(e) {
                            var usedefault = e.target.classList.contains('appliance');
                            calculate(usedefault);
                        })
                    }
                });
            }

            initChange();

            $('#add-appliance').click(function() {
                var data = $(template);
                $(data).insertBefore('#insertdiv');
                var btn = $('.remove', data);
                var select = $('.select2', data);
                initRemove(btn);
                initSelect(select);
                initChange();
                calculate();
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
                    $(e).html(str);
                    if ($(e).data('select2')) {
                        $(e).select2('destroy');
                    }
                    $(e).select2({
                        theme: 'classic'
                    })
                });
            }
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

                    console.log('Found !');
                    var radius = e.accuracy;
                    // L.marker(e.latlng, {
                    //         draggable: 'true'
                    //     }).addTo(map)
                    //     .bindPopup("You are within " + radius + " meters from this point").openPopup();
                    var position = e.latlng;
                    marker.setLatLng(position, {
                        draggable: 'true'
                    })
                    marker.bindPopup("You are within " + radius + " meters from this point, Drag this marker to ajust your position!").update()
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
                        if (addr) {
                            $('[name="address"]').val(addr);
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
                // initMap2();
            } catch (error) {
                console.log(error);
            }

            var uform = $('#uform');

            uform.on('submit', function() {
                event.preventDefault();
                var rep = $('#rep', uform);
                rep.stop().slideUp();
                $(':input', uform).prop('disabled', false);
                var data = uform.serialize();
                $(':input', uform).prop('disabled', true);
                var _data = form.serialize();

                if (_data.length) {
                    data += '&' + _data;
                }

                $.ajax({
                    method: 'POST',
                    url: '{{ route('new_request') }}',
                    data: data,
                    timeout: 20000,
                    success: function(data) {
                        if (data.success) {
                            rep.html(data.message).removeClass().addClass('alert alert-success')
                                .slideDown();
                            uform[0].reset();
                        } else {
                            rep.html(data.message).removeClass().addClass('alert alert-danger')
                                .slideDown();
                        }
                        $(':input', uform).not('[name=address]').prop('disabled', false);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        rep.html("Enable to reach the server").removeClass().addClass(
                                'alert alert-danger')
                            .slideDown();

                    }
                }).always(function() {
                    $(':input', uform).not('[name=address]').prop('disabled', false);
                })
            })

            $('#print').click(function() {
                var html = '<h3 class="text-center">Power calculator</h3>';
                html += `<table class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="6">
                                <b>Items</b>
                            </th>
                        </tr>
                    </thead>
                    <tr>
                        <th></th>
                        <th>Appliance</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Watts</th>
                        <th class="text-center">Hours On per day</th>
                        <th class="text-end">Watts hours per day</th>
                    </tr>
                `;

                $('.item').each(function(i, e) {
                    var app = $(e).find('.appliance').val();
                    var qty = $(e).find('.quantity').val();
                    var watts = $(e).find('.watts').val();
                    var onperday = $(e).find('.hoursperday').val();
                    var totwatts = $(e).find('.wattsperday').val();

                    html += `
                    <tr>
                        <td>${ i+1 }</td>
                        <td>${ app }</td>
                        <td class="text-center">${ qty }</td>
                        <td class="text-center">${ watts } ${ watts > 1 ? 'Watts' : 'Watt' }</td>
                        <td class="text-center">${ onperday }</td>
                        <td class="text-end">${ totwatts } ${ totwatts > 1 ? 'Watts' : 'Watt' } </td>
                    </tr>
                    `;
                })

                var totwattspeak = $('[totwattspeak]').html();
                var totwattsday = $('[totwattsday]').html();
                var totwattsmonth = $('[totwattsmonth]').html();
                var costmonth = $('[costmonth]').html();

                html += `
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
                                <td colspan="3" class="text-end">${ totwattspeak }
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">Total Watt Hours per Day </td>
                                <td colspan="3" class="text-end">${ totwattsday }
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">Kilowatt Hours Per Month</td>
                                <td colspan="3" class="text-end">
                                    <span class="badge bg-danger">
                                        ${ totwattsmonth }
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
                                    <span class="badge bg-danger">${ costmonth } USD</span>
                                </td>
                            </tr>
                        </table>
                    `;
                var pz = $('.printzone');
                pz.html(html);
                pz.printThis()
            })

        })
    </script>
</body>

</html>
