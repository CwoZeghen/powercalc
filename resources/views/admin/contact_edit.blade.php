<html lang="lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Edit contact</title>
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

        .accordion-button:focus {
            box-shadow: none;
        }

        .select2-selection.select2-selection--single,
        .select2-selection__arrow {
            height: 3.5rem !important;
        }

        .select2-container--classic .select2-results__option--highlighted.select2-results__option--selectable,
        .bg-primary {
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
    @include('admin.nav')
    <div class="container">
        <div class="row">
            <div class="col-md-12 pb-5">
                <h4 class="text-center mt-5 mb-5">Edit form</h4>
                <div class="table-responsive">
                    <form class="was-validated" id="form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $appl->id }}">
                        <div class="modal-body">
                            <div class="table-responsive">
                                <div class="mb-5 item-zone" style="max-height: 500px;overflow-y: scroll;">
                                    @php
                                        $d = (object) json_decode($appl->appliance);
                                        $addonsList = array_map(function ($el) {
                                            $el->checked = true;
                                            return $el;
                                        }, (array) @$d->addons);

                                        $notSelectedAddons = [];
                                        $selectedAddons = [];
                                        $image = (array) @$d->image;
                                        $appliance = (array) @$d->appliance;
                                        $watts = (array) @$d->watts;
                                        $qty = (array) @$d->qty;
                                        $onperday = (array) @$d->onperday;
                                        $totwattspeak = $totwattsday = $totwattsmonth = 0;
                                    @endphp
                                    @php
                                        foreach ($addonsList as $el):
                                            $selectedAddons[] = $el->id;
                                        endforeach;
                                        foreach ($addons as $el):
                                            if (!in_array($el->id, $selectedAddons)) {
                                                $o = (object) [];
                                                $o->id = $el->id;
                                                $o->name = $el->name;
                                                $o->cost = $el->cost;
                                                $o->description = $el->cost;
                                                $o->checked = false;
                                                $addonsList[] = $o;
                                            }
                                        endforeach;
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
                                                    value="{{ @$qty[$k] }}" style="width: 100px">
                                                <label for="floatingInput">Quantity</label>
                                            </div>
                                            <div class="form-floating m-2">
                                                <input type="number" class="form-control watts" id="floatingInput"
                                                    value="{{ @$watts[$k] }}" name="watts[]"
                                                    placeholder="Watts (Volts x Amps)" style="width: 190px">
                                                <label for="floatingInput">Watts (Volts x Amps)</label>
                                            </div>
                                            <div class="form-floating m-2">
                                                <input type="number" min="0.01" max="24" step='0.01'
                                                    name="onperday[]" class="form-control hoursperday"
                                                    id="floatingInput" placeholder="Hours On per Day"
                                                    style="width: 160px" value="{{ @$onperday[$k] }}">
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
                                                    <img src="{{ @$image[$k] }}" class="img-thumbnail image-appl"
                                                        alt="image"
                                                        style="cursor: pointer;width: 100px; height: 60px; object-fit:cover">
                                                </label>
                                                <input type="file" class="file-appl" accept=".jpg,.jpeg,.png,.webp"
                                                    id="file-appl-{{ $rand }}" style="display: none">
                                                <input type="hidden" name='image[]' value="{{ @$image[$k] }}"
                                                    class="image-data">
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
                            </div>
                            <button type="button" class="btn btn-link text-danger" id="add-appliance">
                                Add appliance
                            </button>

                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="mt-4">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button bg-light" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                aria-expanded="false" aria-controls="flush-collapseOne">
                                                <h4>Power consumption</h4>
                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse show active">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div
                                                        class="col-md-5 offset-md-7 d-flex justify-content-between text-center">
                                                        <div class="m-1">
                                                            <small>Total Watts(Peak Load)</small>
                                                            <div class="bg-primary text-white p-2 rounded"
                                                                title="{{ $peakload * 100 }} % of total Watt Hours per Day ">
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
                                                            <div class="bg-primary text-white p-2 rounded"
                                                                title="Total kilowatts on 30 days">
                                                                <b totwattsmonth></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
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
                                                            <small>Cost</small> <br>
                                                            <div class="bg-primary text-white p-2 rounded">
                                                                <b>1 kW = {{ $cost }} USD</b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 offset-md-8 text-center">
                                                        <div class="m-3">
                                                            <small>Cost Per Month :</small> <br>
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
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseFive"
                                                aria-expanded="false" aria-controls="flush-collapseFive">
                                                <h4>Addons</h4>
                                            </button>
                                        </h2>
                                        <div id="flush-collapseFive" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    @foreach ($addonsList as $el)
                                                        <div class="col-md-8">
                                                            <h5 class="text-muted">{{ $el->name }}
                                                            </h5>
                                                            <small class="text-primary" showdesc
                                                                style="cursor: pointer">
                                                                <i class="fa fa-caret-down"></i>
                                                                What is the benefit ?</small> <br>
                                                            <i description style="display: none">
                                                                {{ $el->description }}
                                                            </i>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <h5>
                                                                $ {{ $el->cost }}
                                                            </h5>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="d-flex justify-content-end">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" name="addons[]"
                                                                        @if ($el->checked == true) checked @endif
                                                                        price="{{ $el->cost }}" type="checkbox"
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
                                <div class="p-2 mt-3">
                                    <div id="rep"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" onclick="history.back()" class="btn btn-secondary m-3"
                                data-bs-dismiss="modal">Back</a>
                            <button type="submit" class="btn btn-danger">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/b5.js') }}"></script>
    <script src="{{ asset('js/jq.js') }}"></script>
    <script src="{{ asset('js/dt.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/dt.css') }}">

    <script src="{{ asset('js/select2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

    <script>
        $(function() {
            var template = `
            <div class="d-flex item">
                <div class="m-2">
                    <select class="form-select select2 appliance" name="appliance[]" style="min-width: 300px">
                        <option value="">Select appliance</option>
                        @foreach ($appliances as $el)
                        <option watts='{{ $el->watts }}' value="{{ $el->name }}">{{ $el->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-floating m-2">
                    <input type="number" min="1" class="form-control quantity" value="1"
                        name="qty[]" id="floatingInput" placeholder="Quantity" style="width: 100px">
                    <label for="floatingInput">Quantity</label>
                </div>
                <div class="form-floating m-2">
                    <input type="number" class="form-control watts" id="floatingInput" name="watts[]"
                        placeholder="Watts (Volts x Amps)" style="width: 190px">
                    <label for="floatingInput">Watts (Volts x Amps)</label>
                </div>
                <div class="form-floating m-2">
                    <input type="number" min="0.01" max="24" step='0.01' value=1 name="onperday[]"
                        class="form-control hoursperday" id="floatingInput" placeholder="Hours On per Day"
                        style="width: 160px">
                    <label for="floatingInput">Hours On per Day</label>
                </div>
                <div class="form-floating m-2">
                    <input class="form-control wattsperday" readonly id="floatingInput" name="wattsperday[]"
                        placeholder="Watt Hours per Day" style="width: 200px">
                    <label for="floatingInput">Watt Hours per Day</label>
                </div>
                <div class="m-2 img-div">
                    <label for="file-appl-RANDATA">
                        <img src="_SOURCE_" class="img-thumbnail image-appl" alt="image" style="cursor: pointer;width: 100px; height: 60px; object-fit:cover">
                    </label>
                    <input type="file" class="file-appl" id="file-appl-RANDATA" accept=".jpg,.jpeg,.png,.webp"
                        style="display: none">
                    <input type="hidden" name='image[]' value="_SOURCE_" class="image-data" >
                </div>
                <div class="m-2">
                    <button type="button" class="btn btn-lg btn-danger remove btn-close text-danger mt-3"
                        aria-label="Close"></button>
                </div>
            </div>
            `;

            COST = '{{ $cost }}';
            COST = Number(COST);
            COST = COST ? COST : 0;

            PEAKLOAD = '{{ $peakload }}';
            PEAKLOAD = Number(PEAKLOAD);
            PEAKLOAD = PEAKLOAD ? PEAKLOAD : 0;

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
                $('select, input', form).each(function(e) {
                    var el = $(this);
                    if (!el.data('change')) {
                        el.change(function(e) {
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

            $('#add-appliance').click(function() {
                var rand = Math.random().toString().split('0.').join('');
                var _template = template;
                _template = _template.split('RANDATA').join(rand);
                _template = _template.split('_SOURCE_').join(icon);


                var data = $(_template);
                $(data).insertBefore('#insertdiv');
                var btn = $('.remove', data);
                var select = $('.select2', data);
                initRemove(btn);
                initSelect(select);
                initChange();
                calculate();
                initFileChange();
                const element = $('.item-zone');
                element.stop();
                element.animate({
                    scrollTop: element.prop("scrollHeight")
                }, 1000);
            });
            $('#clearall').click(function() {
                form[0].reset();
                $(".select2").val(null).trigger("change");
            })

            function initRemove(item = null) {
                if (item == null) {
                    item = $('.remove');
                }
                item.each(function(i, e) {
                    var item = $(this);
                    item.click(function() {
                        item.closest('.item').remove();
                        calculate();
                    });
                })

            }

            function initSelect(item = null) {
                if (item == null) {
                    item = $('.select2');
                }

                $(item).each(function(i, e) {
                    if ($(e).data('select2')) {
                        $(e).select2('destroy');
                    }
                    $(e).select2({
                        theme: 'classic'
                    })
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

                } else {
                    $('[totprice]').html('0 USD');
                    $('[taddon]').slideUp();
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

            form.on('submit', function() {
                event.preventDefault();
                var rep = $('#rep', form);
                rep.stop().slideUp();
                $(':input', form).prop('disabled', false);
                var data = form.serialize();
                $(':input', form).prop('disabled', true);

                $.ajax({
                    method: 'POST',
                    url: '{{ route('data.contact.update') }}',
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
                                location.href =
                                    '{{ route('admin.contact.edit', $appl->id) }}';
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
                })
            })


            var icon =
                'data:image/png;base64,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g1nmi4DQ+OxjPtAfP0bkkBPwooXPEct8ERAakw389z7b51X8G5epbdNsygotBITaZh/jAc8+tl/F74pI2/8+RkQQEGqffbAjIsWKp2lHnr+ut46AkHz2EV+pOtH8PyLzrXFKvd9ViudwLiIICClZAvrKuGydfFPttptaboUWdTsxBIOYffRtw8QUJ9/478Oo258RnRU/x5fr25tnv1bMQKjCwhDsjki4rXrwnIizKddbR0CoZPYRTyhW6uxn0qOfwwotBISjWXk13BiKCAJC6dlHVz8Qprrn9wcrtBAQDo1H15ajkk6+vVwZBIS3zP1+2bC0zBcBYd/Zh1ecbIvLfFdWaCEgvGXhd8sr4tUNLfNFQHhx9hE/NPehKW9xvXUEhBf54Jx9jIuZyKmhQEBYsz0Nhzz/7eaLgACl5cW130FAgINllvkiIEBZrreOgAClrffQsgUOAgKUiohlvggIUPrcEGciM0OBgABlzg8ry3wREKAs11vnRf7oDNiH661jBgKUj8jIRowICFCSFVoICFDaeiNGEREQgFLnDtdbdxAAlGYjRgEBKM1GjANlGW//3Ifbg2GgbvHiVNe3N449AaGrwhP43igAdfAWFgACAoCAACAgAAgIAAgIAAICgIAAICAACAgACAgAAgJAU2ym2F7Tq4vL3w0DYAYCgIAAgIAAICAACAgAAgKAgACAgAAgIAAICAACAoCAAICAACAgAAgIAAICgIAAgIAAICAACAgAXXRiCI72GG53hgE658kQAEADvIUFgIAAICAACAgAAgIAAgKAgAAgIAAICAACAgACAoCAACAgAAgIAAICAAICgIAAICAACAgAAgIAAgKAgAAgIAAICAACAgACAoCAACAgAAgIAAICAAICgIAAICAACAgAAgIAAgKAgAAgIAAICAAICAACAoCAACAgAAgIAAgIAAICgIAAICAACAgACAgAAgKAgAAgIAAICAAICAACAoCAACAgAAgIAAgIAAICgIAAICAACAgACAgAAgKAgAAgIAAICAAICAACAoCAACAgAAiIIQBAQAAQEAAEBAABAQABAUBAABAQAAQEAAEBAAEBQEAAEBAABAQAAQEAAQFAQAAQEAAEBAABAQABAUBAABAQAAQEAAEBAAEBQEAAEBAABAQAAQEAAQFAQAAQEAAEBAABAQABAUBAABAQAAQEAAQEgJL+E2AAvv8YFBUVTjoAAAAASUVORK5CYII=';

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
        })
    </script>
</body>

</html>
