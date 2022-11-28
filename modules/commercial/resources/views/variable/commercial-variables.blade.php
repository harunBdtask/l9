@extends('skeleton::layout')
@section('title','Commercial Variable')

@push('style')
    <style>
        .form-control form-control-sm {
            border: 1px solid #909ac8 !important;
            border-radius: 10px 0 0 0;
        }

        input, select {
            min-height: 30px !important;
        }

        .form-control form-control-sm:focus {
            border: 2px solid #909ac8 !important;
        }

        .req {
            font-size: 1rem;
        }

        .mainForm td, .mainForm th {
            border: none !important;
            padding: .3rem !important;
        }

        li.parsley-required {
            color: red;
            list-style: none;
            text-align: left;
        }

        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error {
            border-color: #843534;
            box-shadow: none;
        }


        input.parsley-error:focus,
        select.parsley-error:focus,
        textarea.parsley-error:focus {
            border-color: #843534;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 6px #ce8483
        }

        .remove-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #843534;
            color: whitesmoke;
        }

        .close-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #6cc788;
            color: whitesmoke;
        }

        .error + .select2-container .select2-selection--single {
            border: 1px solid red;
        }

        fieldset.scheduler-border {
            border: 1px solid #909ac8 !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            padding: 0 10px;
            border-bottom: none;
        }

    </style>
@endpush

@section('content')
    <div class="padding">


        <div class="box" >
            <div class="box-header text-center">
                <h2 style="font-weight: 400; ">Commercial Variables</h2>
            </div>

            <div class="box-body">
                @include('commercial::partials.flash')
                @include('commercial::variable.partials.variable-list')
            </div>

        </div>
    </div>
@endsection

@push('script-head')
    <script>
        $(document).ready(function () {
            const $selectFactory = $('#selectFactory');
            const $selectVariable = $('#selectVariable');
            const $variableDetails = $('#variable-details');
            const $body = $('body');
            let FACTORY;

            $body.on('change', '#selectFactory', function (e) {
                FACTORY = $selectFactory.find(":selected").val();
                $variableDetails.html(null);
                $selectVariable.val(null);

            })


            $body.on('change', '#selectVariable', async function (e) {
                $variableDetails.html(null);
                const TARGET_NAME = e.target.name;
                const VALUE = e.target.value;
                FACTORY = $selectFactory.find(":selected").val();
                console.log(FACTORY)


                try {
                    if (VALUE === 'btb_limit_percent') {
                        res = await axios.get(`/commercial/variable-settings-form?value=${VALUE}&factory=${FACTORY}`);
                        $('#variable-details').html(res.data)
                    } else {
                        $('#variable-details').html(null)
                    }
                } catch (e) {
                    console.log(e)
                }

            })

            $('#form').parsley();

        })
    </script>
@endpush
