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
                            <h5 class="text-dashboard fw-bolder">Nilai Barang Masuk</h5>
                            <span>IDR</span>
                            <span style="font-size: 7.7vh ;font-weight:bold;" id="nilaiBarangMasuk">-</span>
                        </div>
                        <div class="d-block text-end">
                            <div class="form-group">
                                <select name="" class="barangmasuk-daterange" id="barangMasukBulan">
                                    <option value="all">All</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <select name="" class="barangmasuk-daterange" id="barangMasukTahun">
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
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
                            <h5 class="text-dashboard fw-bolder">Nilai Barang Keluar</h5>
                            <span>IDR</span>
                            <span style="font-size: 7.7vh ;font-weight:bold;" id="nilaiBarangKeluar">-</span>
                        </div>
                        <div class="d-block text-end">
                            <div class="form-group">
                                <select name="" class="barangkeluar-daterange" id="barangKeluarBulan">
                                    <option value="all">All</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <select name="" class="barangkeluar-daterange" id="barangKeluarTahun">
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
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
                            <h5 class="text-dashboard fw-bolder">Saldo Barang Persediaan</h5>
                            <span>IDR</span>
                            <span style="font-size: 7.7vh ;font-weight:bold;" id="nilaiSaldoBarang">-</span>
                        </div>
                        <div class="d-block text-end">
                            <div class="form-group">
                                <select name="" class="saldobarang-daterange" id="saldoBarangBulan">
                                    <option value="all">All</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <select name="" class="saldobarang-daterange" id="saldoBarangTahun">
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
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
                                <div class="d-block mb-3">
                                    <h5 class="text-dashboard fw-bolder">Permintaan Disetujui</h5>
                                    <span style="font-size: 7.7vh ;font-weight:bold;" id="nilaiPermintaanDisetujui">-</span>
                                </div>
                                <div class="d-block text-end">
                                    <div class="form-group">
                                        <select name="" class="permintaandisetujui-daterange" id="permintaanDisetujuiBulan">
                                            <option value="all">All</option>
                                            <option value="01">Januari</option>
                                            <option value="02">Februari</option>
                                            <option value="03">Maret</option>
                                            <option value="04">April</option>
                                            <option value="05">Mei</option>
                                            <option value="06">Juni</option>
                                            <option value="07">Juli</option>
                                            <option value="08">Agustus</option>
                                            <option value="09">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                        <select name="" class="permintaandisetujui-daterange" id="permintaanDisetujuiTahun">
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-12">
                        <div class="card bg-danger text-dashboard" style="border:1px solid;">
                            <div class="card-body p-3">
                                <div class="d-block mb-3">
                                    <h5 class="text-dashboard fw-bolder">Permintaan Ditolak</h5>
                                    <span style="font-size: 7.7vh ;font-weight:bold;" id="nilaiPermintaanDitolak">-</span>
                                </div>
                                <div class="d-block text-end">
                                    <div class="form-group">
                                        <select name="" class="permintaanditolak-daterange" id="permintaanDitolakBulan">
                                            <option value="all">All</option>
                                            <option value="01">Januari</option>
                                            <option value="02">Februari</option>
                                            <option value="03">Maret</option>
                                            <option value="04">April</option>
                                            <option value="05">Mei</option>
                                            <option value="06">Juni</option>
                                            <option value="07">Juli</option>
                                            <option value="08">Agustus</option>
                                            <option value="09">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                        <select name="" class="permintaanditolak-daterange" id="permintaanDitolakTahun">
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card bg-card-dashboard text-dashboard" style="border:1px solid;">
                    <div class="card-body p-3">
                        <div class="d-block mb-4">
                            <h5 class="text-dashboard fw-bolder">Permintaan Dalam Proses</h5>
                            <span style="font-size: 7.7vh ;font-weight:bold;" id="nilaiPermintaanDalamproses">-</span>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-card-dashboard text-dashboard" style="border:1px solid;">
                    <div class="card-body p-3">
                        <div class="d-block mb-3">
                            <h5 class="text-dashboard fw-bolder">Barang Yang Telah Didistribusikan</h5>
                            <span style="font-size: 7.7vh ;font-weight:bold;" id="nilaiDistribusi">-</span>
                            <span>Item Barang</span>
                        </div>
                        <div class="d-block text-end">
                            <div class="form-group">
                                <select name="" class="distribusi-daterange" id="distribusiBulan">
                                    <option value="all">All</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <select name="" class="distribusi-daterange" id="distribusiTahun">
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-warning text-dashboard" style="border:1px solid;">
                    <div class="card-body p-3">
                        <div class="d-block mb-3">
                            <h5 class="text-dashboard fw-bolder">Barang Yang Belum Didistribusikan</h5>
                            <span style="font-size: 7.7vh ;font-weight:bold;" id="nilaiBelumDistribusi">-</span>
                            <span>Item Barang</span>
                        </div>
                        <div class="d-block text-end">
                            <div class="form-group">
                                <select name="" class="belumdistribusi-daterange" id="belumdistribusiBulan">
                                    <option value="all">All</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <select name="" class="belumdistribusi-daterange" id="belumdistribusiTahun">
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                </select>

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
            data: @json($kategori)
        },
        yAxis: {
            type: 'value'
        },
        tooltip:{
          show:true
        },
        series: [
            {
            data: @json($saldo),
            type: 'bar'
            }
        ]
        };

        option && myChart.setOption(option);


        $('.barangmasuk-daterange').on('change',function(){
            getBarangMasuk();
        })
        getBarangMasuk()
        async function getBarangMasuk(){
            let year = $('#barangMasukTahun').val();
            let month = $('#barangMasukBulan').val();
            let resp = await axios.post(@json(route('api.nilai-barang-masuk')),{
                year:year,
                month:month
            });
            $('#nilaiBarangMasuk').text(resp.data.total)
        }

        $('.barangkeluar-daterange').on('change',function(){
            getBarangKeluar();
        })
        getBarangKeluar()
        async function getBarangKeluar(){
            let year = $('#barangKeluarTahun').val();
            let month = $('#barangKeluarBulan').val();
            let resp = await axios.post(@json(route('api.nilai-barang-keluar')),{
                year:year,
                month:month
            });
            $('#nilaiBarangKeluar').text(resp.data.total)
        }


        $('.saldobarang-daterange').on('change',function(){
            getSaldoBarang();
        })
        getSaldoBarang()
        async function getSaldoBarang(){
            let year = $('#saldoBarangTahun').val();
            let month = $('#saldoBarangBulan').val();
            let resp = await axios.post(@json(route('api.saldo-barang')),{
                year:year,
                month:month
            });
            $('#nilaiSaldoBarang').text(resp.data.total)
        }


        $('.permintaandisetujui-daterange').on('change',function(){
            getPermintaanDisetujui();
        })
        getPermintaanDisetujui()
        async function getPermintaanDisetujui(){
            let year = $('#permintaanDisetujuiTahun').val();
            let month = $('#permintaanDisetujuiBulan').val();
            let resp = await axios.post(@json(route('api.permintaan-disetujui')),{
                year:year,
                month:month
            });
            $('#nilaiPermintaanDisetujui').text(resp.data.total)
        }


        $('.permintaanditolak-daterange').on('change',function(){
            getPermintaanDitolak();
        })
        getPermintaanDitolak()
        async function getPermintaanDitolak(){
            let year = $('#permintaanDitolakTahun').val();
            let month = $('#permintaanDitolakBulan').val();
            let resp = await axios.post(@json(route('api.permintaan-ditolak')),{
                year:year,
                month:month
            });
            $('#nilaiPermintaanDitolak').text(resp.data.total)
        }

        getPermintaandalamProses()
        async function getPermintaandalamProses(){
            let resp = await axios.post(@json(route('api.permintaan-dalamproses')));
            $('#nilaiPermintaanDalamproses').text(resp.data.total)
        }


        $('.distribusi-daterange').on('change',function(){
            getDistribusi();
        })
        getDistribusi()
        async function getDistribusi(){
            let year = $('#distribusiTahun').val();
            let month = $('#distribusiBulan').val();
            let resp = await axios.post(@json(route('api.nilai-distribusi')),{
                year:year,
                month:month
            });
            $('#nilaiDistribusi').text(resp.data.total)
        }

        $('.belumdistribusi-daterange').on('change',function(){
            getBelumDistribusi();
        })
        getBelumDistribusi()
        async function getBelumDistribusi(){
            let year = $('#belumdistribusiTahun').val();
            let month = $('#belumdistribusiBulan').val();
            let resp = await axios.post(@json(route('api.nilai-belumdistribusi')),{
                year:year,
                month:month
            });
            $('#nilaiBelumDistribusi').text(resp.data.total)
        }
    </script>


    @endpush
