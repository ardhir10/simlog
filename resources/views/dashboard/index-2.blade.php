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

</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin="" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css'
    rel='stylesheet' />
<!-- Make sure you put this AFTER Leaflet's CSS -->
<link rel="stylesheet" href="{{asset('assets/css/maps.css')}}">


@endpush

@section('content')
<div class="page-content">
    <div class="container-fluid p-0">
        <!-- start page title -->
        {{-- <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Dashboard</h4>
                </div>
            </div>
        </div> --}}
        <!-- end page title -->


        <div class="row ">
            <div class="col-lg-4">
                <div class="card">
                        <div class="card-body">

                            <form action="">
                                <div class="row mb-3">
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label for="" class="">Type :</label>
                                            <select name="type" class="form-select" id="">
                                                <option {{Request::get('type') == 'bulan' ? 'selected=selected' : '' }} value="bulan" >Bulan</option>
                                                <option {{Request::get('type') == 'tahun' ? 'selected=selected' : '' }} value="tahun">Tahun</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="" class="">Penyelenggara :</label>
                                            <select name="penyelenggara" class="form-select" id="">
                                                <option value="">Semua</option>
                                                <option {{Request::get('penyelenggara') == 'typeNonDpjl' ? 'selected=selected' : '' }} value="typeNonDpjl" >NON-DJPL</option>
                                                <option {{Request::get('penyelenggara') == 'typeDpjl' ? 'selected=selected' : '' }} value="typeDpjl">DJPL</option>
                                            </select>
                                        </div>
                                    </div> --}}


                                    <div class="col-lg-4">
                                        <label for="" class="text-white">.</label>
                                        <button class="btn btn-primary w-100">Filter</button>
                                    </div>
                                    {{-- <div class="col-lg-1">
                                        <label for="" class="text-white">.</label>
                                        <a href="{{route('dashboard')}}" class="btn btn-danger w-100">Reset</a>
                                    </div> --}}
                                </div>
                            </form>
                        </div>

                    </div>

            </div>
            <div class="col-12">
                <div class="card p-3" style="border-radius: 23px;">
                    <div class="card-body">
                        <div id="line-chart" style="height: 400px;"> </div>

                    </div>
                </div>

            </div> <!-- end row-->






        </div>
        <!-- container-fluid -->
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

        var chartDom = document.getElementById('line-chart');
        var myChart = echarts.init(chartDom);
        var option;

        option = {
            title: {
                text: 'Grafik Laporan (%)'
            },
            tooltip: {
                trigger: 'axis'
            },

            legend: {
                data: ['Kondisi Teknis', 'Keandalan', 'Kelainan']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: @json($data_bulan)
                // data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sept','Oct','Nov','Dec']
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name: 'Kondisi Teknis',
                    type: 'line',
                    // stack: 'Total',
                    itemStyle: {
                        color: "#0EC244"
                    },
                    data: @json($data_chart['kondisiTeknis'])
                },
                {
                    name: 'Keandalan',
                    type: 'line',
                    // stack: 'Total',
                    itemStyle: {
                        color: "#0BB97A"
                    },
                    data: @json($data_chart['keandalan'])
                },
                {
                    name: 'Kelainan',
                    type: 'line',
                    // stack: 'Total',
                    itemStyle: {
                        color: "#F34E4E"
                    },
                    data: @json($data_chart['kelainan'])
                },

            ]
        };

        option && myChart.setOption(option);


    </script>


    @endpush
