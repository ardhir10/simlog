@extends('main')


@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.easy-pie-chart/1.0.1/jquery.easy-pie-chart.css">

<style>
    .card-1 {
        --animate-duration: 0.5s;
    }

    .card-2 {
        --animate-duration: 0.8s;
    }

    .card-3 {
        --animate-duration: 1.1s;
    }


    .card-11 {
        --animate-duration: 0.5s;
    }

    .card-21 {
        --animate-duration: 0.8s;
    }

    .card-31 {
        --animate-duration: 1.1s;
    }

    .card-1 {
        background-image: linear-gradient(109.6deg, rgba(45, 116, 213, 1) 11.2%, rgba(121, 137, 212, 1) 91.2%);
        /* min-height: 150px !important; */
        color: white !important;
        border: 0px solid black !important;
        border-radius: 25px !important;
        box-shadow: 1px 3px 28px -7px rgb(0 0 0 / 71%);
        -webkit-box-shadow: 1px 3px 16px -7px rgb(0 0 0 / 71%);
        -moz-box-shadow: 1px 3px 28px -7px rgba(0, 0, 0, 0.71);

    }

    .card-2 {
        background-image: radial-gradient(circle 610px at 5.2% 51.6%, rgba(5, 8, 114, 1) 0%, rgba(7, 3, 53, 1) 97.5%);
        /* min-height: 150px !important; */
        color: white !important;
        border: 0px solid black !important;
        border-radius: 25px !important;
        box-shadow: 1px 3px 28px -7px rgb(0 0 0 / 71%);
        -webkit-box-shadow: 1px 3px 16px -7px rgb(0 0 0 / 71%);
        -moz-box-shadow: 1px 3px 28px -7px rgba(0, 0, 0, 0.71);

    }

    .card-3 {
        background-image: radial-gradient(circle farthest-corner at 10% 20%, rgba(0, 152, 155, 1) 0.1%, rgba(0, 94, 120, 1) 94.2%);
        /* min-height: 150px !important; */
        color: white !important;
        border: 0px solid black !important;
        border-radius: 25px !important;
        box-shadow: 1px 3px 28px -7px rgb(0 0 0 / 71%);
        -webkit-box-shadow: 1px 3px 16px -7px rgb(0 0 0 / 71%);
        -moz-box-shadow: 1px 3px 28px -7px rgba(0, 0, 0, 0.71);

    }

    .text-card-d1 {
        font-size: 20px;
        font-weight: 500;
    }

    .text-card-d2 {
        display: block;
        font-size: 57px !important;
        font-weight: bolder;
        color: white;
        vertical-align: top;
    }

    #countParentLocation {
        display: block;
        font-size: 20px;
    }

    .detailSbnp {
        border-radius: 10px;
        border: solid 1px black;
        /* height: 200px; */
    }

    .nav-tabs-custom .nav-item {
        position: relative;
        border-radius: -2px;
        color: #343a40;
        border: solid 0.5px;
    }

    .tab-pane {
        min-height: 45vh !important;
    }


    .global-chart {
        font-size: 28px;
        font-weight: bold !important;
    }

    .global-chart-detail {
        font-size: 18px;
        font-weight: bold !important;
    }

    .nav-tabs-custom .nav-item .nav-link.active {
        color: white;
        background: #038edc !important;
    }
    .nav-tabs .nav-link {
        border-top-left-radius: 0px !important;
        border-top-right-radius: 0px !important;
    }
    .nav-tabs-custom .nav-item .nav-link::after {
        display: none;

    }

    .bg-card-dashboard{
        background: #C0F4DE !important;
    }

    .text-dashboard{
        color: #0D6749 !important;
    }
</style>


@endpush

@section('content')
<div class="page-content">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4">
                <div class="card bg-card-dashboard text-dashboard" style="border:1px solid;">
                    <div class="card-body p-3">
                        <div class="d-block mb-3">
                            <h3 class="text-dashboard fw-bolder">Nilai Barang Masuk</h3>
                            <span>IDR</span>
                            <span style="font-size: 7.7vh ;font-weight:bold;">365.000.000</span>
                        </div>
                        <div class="d-block text-end">
                            <div class="form-group">
                                <select name="" class="" id="">
                                    <option value="">Januari</option>
                                    <option value="">Februari</option>
                                    <option value="">Maret</option>
                                    <option value="">April</option>
                                    <option value="">Mei</option>
                                    <option value="">Juni</option>
                                    <option value="">Juli</option>
                                    <option value="">Agustus</option>
                                    <option value="">September</option>
                                    <option value="">Oktober</option>
                                    <option value="">November</option>
                                    <option value="">Desember</option>
                                </select>
                                <select name="" class="form-sekect" id="">
                                    <option value="">2022</option>
                                    <option value="">2021</option>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-card-dashboard text-dashboard" style="border:1px solid;">
                    <div class="card-body p-3">
                        <div class="d-block mb-3">
                            <h3 class="text-dashboard fw-bolder">Nilai Barang Keluar</h3>
                            <span>IDR</span>
                            <span style="font-size: 7.7vh ;font-weight:bold;">180.330.750</span>
                        </div>
                        <div class="d-block text-end">
                            <div class="form-group">
                                <select name="" class="" id="">
                                    <option value="">Januari</option>
                                    <option value="">Februari</option>
                                    <option value="">Maret</option>
                                    <option value="">April</option>
                                    <option value="">Mei</option>
                                    <option value="">Juni</option>
                                    <option value="">Juli</option>
                                    <option value="">Agustus</option>
                                    <option value="">September</option>
                                    <option value="">Oktober</option>
                                    <option value="">November</option>
                                    <option value="">Desember</option>
                                </select>
                                <select name="" class="form-sekect" id="">
                                    <option value="">2022</option>
                                    <option value="">2021</option>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-card-dashboard text-dashboard" style="border:1px solid;">
                    <div class="card-body p-3">
                        <div class="d-block mb-3">
                            <h3 class="text-dashboard fw-bolder">Saldo Barang Persediaan</h3>
                            <span>IDR</span>
                            <span style="font-size: 7.7vh ;font-weight:bold;">976.489.000</span>
                        </div>
                        <div class="d-block text-end">
                            <div class="form-group">
                                <select name="" class="" id="">
                                    <option value="">Januari</option>
                                    <option value="">Februari</option>
                                    <option value="">Maret</option>
                                    <option value="">April</option>
                                    <option value="">Mei</option>
                                    <option value="">Juni</option>
                                    <option value="">Juli</option>
                                    <option value="">Agustus</option>
                                    <option value="">September</option>
                                    <option value="">Oktober</option>
                                    <option value="">November</option>
                                    <option value="">Desember</option>
                                </select>
                                <select name="" class="form-sekect" id="">
                                    <option value="">2022</option>
                                    <option value="">2021</option>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card bg-card-dashboard text-dashboard" style="border:1px solid;">
                    <div class="card-body p-3">
                        <div id="main" style="height:370px;">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card bg-card-dashboard text-dashboard" style="border:1px solid;">
                            <div class="card-body p-3">
                                <div class="d-block mb-1">
                                    <h3 class="text-dashboard fw-bolder">Permintaan Disetujui</h3>
                                    <span style="font-size: 4vw;font-weight:bold;">34</span>
                                </div>
                                <div class="d-block text-end">
                                    <div class="form-group">
                                        <select name="" class="" id="">
                                            <option value="">Januari</option>
                                            <option value="">Februari</option>
                                            <option value="">Maret</option>
                                            <option value="">April</option>
                                            <option value="">Mei</option>
                                            <option value="">Juni</option>
                                            <option value="">Juli</option>
                                            <option value="">Agustus</option>
                                            <option value="">September</option>
                                            <option value="">Oktober</option>
                                            <option value="">November</option>
                                            <option value="">Desember</option>
                                        </select>
                                        <select name="" class="form-sekect" id="">
                                            <option value="">2022</option>
                                            <option value="">2021</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card bg-danger text-white" style="border:1px solid;">
                            <div class="card-body p-3">
                                <div class="d-block mb-1">
                                    <h3 class="text-white fw-bolder">Permintaan Ditolak</h3>
                                    <span style="font-size: 4vw;font-weight:bold;">11</span>
                                </div>
                                <div class="d-block text-end">
                                    <div class="form-group">
                                        <select name="" class="" id="">
                                            <option value="">Januari</option>
                                            <option value="">Februari</option>
                                            <option value="">Maret</option>
                                            <option value="">April</option>
                                            <option value="">Mei</option>
                                            <option value="">Juni</option>
                                            <option value="">Juli</option>
                                            <option value="">Agustus</option>
                                            <option value="">September</option>
                                            <option value="">Oktober</option>
                                            <option value="">November</option>
                                            <option value="">Desember</option>
                                        </select>
                                        <select name="" class="form-sekect" id="">
                                            <option value="">2022</option>
                                            <option value="">2021</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')


    <script src="https://cdn.jsdelivr.net/jquery.easy-pie-chart/1.0.1/jquery.easy-pie-chart.js"></script>
    <script src="{{ asset('/assets') }}/libs/echart/echarts.min.js"></script>
    <script src="{{ asset('/assets') }}/libs/axios/axios.min.js"></script>


    <script>
        $('.nav-link').on('click', function () {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
        })


        var chartDom = document.getElementById('main');
        var myChart = echarts.init(chartDom);
        var option;

        option = {
        xAxis: {
            type: 'category',
            data: ['Bahan Baku', 'Suku Cadang', 'Komaliwan', 'Berjaga-Jga', 'Lain-Lain']
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
            data: [380000000, 600000000, 560000000, 690000000, 600000000],
            type: 'bar'
            }
        ]
        };

        option && myChart.setOption(option);


    </script>


    @endpush
