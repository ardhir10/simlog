<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$data->nomor_nota_dinas}} (Nota Dinas)</title>
    <style>
        .title{
            /* margin-top: 10px; */
        }
        .middle{
            margin-top: 40px;
            padding: 0px 20px;
        }
        .middle table tr td{
            padding: 10px 0px;
        }
        .middle table tr td:first-child {
            padding: 10px 0px;
            width: 39%;
        }
        .middle table tr td:last-child {
            padding: 10px 0px;
            width: 60%;
        }
        .uppercase{
             text-transform: uppercase;
        }
        .footer{
             margin-top: 20px;
            padding: 0px 60px;
        }
    </style>
</head>

<body style='font-family: proxima-nova,"Helvetica Neue",Helvetica,Arial,sans-serif '>
    <div class="header">
        <table>
            <tr>
                <td>
                    <img src="https://simlog.disnavpriok.id/assets/images/icon/kemenhub.png" height="130px" alt=""></td>
                <td style="vertical-align: top;">
                    <div style="padding-left:20px;width:100%">
                        <div>
                            <span style="font-size:2vw;font-size: 35px;display:block;font-weight:bolder; ">KEMENTERIAN PERHUBUNGAN</span>
                            <span style="font-size: 1vw;font-size:23px;display:block;font-weight:bolder; ">DIREKTORAT JENDERAL PERHUBUNGAN LAUT</span>
                            <span style="font-size: 1vw;font-size:24.5px;display:block;font-weight:bolder; ">DISTRIK NAVIGASI KELAS I TANJUNG PRIOK</span>
                        </div>
                        <div>
                            <table style="font-size:10px">
                                <tr>
                                    <td style="vertical-align: top;">
                                        <div style="margin-left:-4px">
                                            <table style="">
                                                <tr>
                                                    <td>Jl. Raya Ancol Baru, Tanjung Priok</td>
                                                </tr>
                                                <tr>
                                                    <td>Jakarta Utara , 14310</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td style="vertical-align: top;font-size:10px">
                                        <div style="padding-left: 10px;">
                                            <table style="">
                                                <tr>
                                                    <td>TELP</td>
                                                    <td style="white-space: nowrap">: (021) 4393, 0070</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="white-space: nowrap">: (021) 4393, 1849</td>
                                                </tr>
                                                <tr>
                                                    <td>FAX</td>
                                                    <td style="white-space: nowrap">: (021) 4393, 0534</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </td>
                                    <td style="vertical-align: top;">
                                        <div style="padding-left:10px;">
                                            <table style="">
                                                <tr>
                                                    <td>
                                                        <img src="{{ asset('/images/icon/browser.png') }}" width="10" alt="">
                                                        {{-- <img src="{{ public_path('/images/icon/browser.png') }}"  width="10" alt=""> --}}
                                                    </td>
                                                    <td >: <span style="font-size: 10px">https://hubla.dephub.go.id/disnavtanjungpriok</span></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <img src="{{ asset('/images/icon/mail.png') }}" width="10" alt="">
                                                        {{-- <img src="{{ public_path('/images/icon/mail.png') }}"  width="10" alt=""> --}}

                                                    </td>
                                                    <td>: disnavtanjungpriok@dephub.go.id</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </td>
            </tr>
        </table>
    </div>
    <hr>
    <div class="body" style="padding: 30px;">
        <div class="main">
            {{-- <div class="top">
                <div style="text-align: right;">
                </div>
            </div> --}}

            <div class="title" >
                <div style="text-align: center;">
                    <span style="font-weight:bold;display:block;">NOTA DINAS</span>
                    <span style="font-weight:bold;">{{$data->nomor_nota_dinas}}</span>
                </div>
            </div>
            <div class="middle">
                Yth. Kepada Distrik Navigasi Kelas I Tanjung Priok
                <div>
                    <table style="width: 100%">
                        <tr>
                            <td>Dari</td>
                            <td style="width: 1%">:</td>
                            <td> {{$data->dimintaOleh() ?? null}}</td>
                        </tr>
                        <tr>
                            <td>Hal</td>
                            <td style="width: 1%">:</td>
                            <td> {{$data->perihal}}</td>
                        </tr>

                        <tr>
                            <td>Tanggal</td>
                            <td style="width: 1%">:</td>
                            <td> {{date('d-m-Y H:i:s',strtotime(date('Y-m-d'))) }}</td>
                        </tr>
                    </table>
                </div>
                <div style="margin-top:50px;">
                    <span>Dalam rangka menunjang kegiatan operasional pada bagian/seksi/instalasi kami, dibutuhkan barang-barang baik barang persediaan maupun barang asset.</span>
                    <br>
                    <br>
                    <span>Sehubungan butir tersebut diatas, bersama ini kamu sampaikan daftar kebutuhan barang yang diminta. (Terlampir)</span>
                    <br>
                    <br>
                    <span>Demikian disampaikan, mohon petunjuk dan arahan lebih lanjut</span>
                </div>

            </div>

        </div>
    </div>



    <div class="footer">
        <div style="float:right;width:40%;text-align: left;">
            <div style="margin-bottom:25px;">
                <span style="display: block">Kepala {{$data->bagianBidang()}}</span>
                <span style="display: block">Distrik Navigasi Kelas I Tanjung Priok</span>
            </div>
            {!! '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG(route('public-data.user',$data->user_id ?? 0), 'QRCODE',3,3) . '" alt="barcode"   />' !!}
            <span style="display: block">{{$data->kepalaBagiannya() ?? '-'}}</span>
            <span style="display: block">{{$data->user->nip ?? '-'}}</span>
        </div>
        <div class="left-note" style="padding: 0px 50px;margin-top:230px;margin-left:-60px">
            <span class="" style="display: block ;font-size:12px;"> Tembusan :</span>
            <span class="" style="display: block ;font-size:12px;">1. Kepala Bagian Tata usaha Disnav Kelas I Tg. Priok</span>
            <span class="" style="display: block ;font-size:12px;">2. Kepala Bidang Operasi</span>
            <span class="" style="display: block ;font-size:12px;">3. Kepala Bidang Logistik Disnav Kelas I Tg. Priok</span>
        </div>
    </div>


</body>

</html>
