<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$data->nomor_upp4}} (Nomor UPP4)</title>
    <style>
        .title{
            /* margin-top: -10px; */
        }
        .middle{
            margin-top: 20px;
            /* padding: 0px 20px; */
        }
        .middle table tr td{
            /* padding: 10px 0px; */
        }
        .middle table tr td:first-child {
            /* padding: 10px 0px; */
            width: 39%;
        }
        .middle table tr td:last-child {
            /* padding: 10px 0px; */
            width: 60%;
        }
        .uppercase{
             text-transform: uppercase;
        }
        .footer{
             margin-top: 20px;
            /* padding: 0px 30px; */
        }

        .table{
            margin-top:30px;
            width: 100%;
            border: 1px #252526 solid;
            border-collapse: collapse;
            font-size: 12px;
        }
        .table tr td{
            width: 100%;
            border: 1px #252526 solid;
            border-collapse: collapse;
            padding: 3px 8px;
        }
        .table tbody{
            width: 100%;
            font-size: 10px;
        }
        .table thead tr td {
            width: 100%;
            padding: 3px 8px;
            font-size: 10px;
            text-align: center;
        }

        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>

<body style='font-family: proxima-nova,"Helvetica Neue",Helvetica,Arial,sans-serif '>

    <div class="header">
        <table>
            <tr>
                <td>
                    <img src="{{asset('assets/images/icon/kemenhub.png')}}" height="130px" alt=""></td>
                </td>
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
    <div class="body" style="padding:0px 30px;">
        <div class="main">

            <div class="title" >
                <div style="text-align: center;">
                    <span style="font-weight:bold;display:block;">BERITA ACARA SERAH TERIMA BARANG</span>
                    <span style="font-weight:bold;">{{$data->nomor_bast}} / {{$data->nomor_upp4}}</span>

                </div>
            </div>
            <div class="middle">
                <div>
                    <table style="width: 100%">
                        <tr>
                            <td>Pada Hari ini Tanggal {{date('Y-m-d',strtotime($data->bast_at))}} Pukul {{date('H:i:s',strtotime($data->bast_at))}}, telah dilakukan serah terima barang yang tercantum pada Surat Berita Acara Serah Terima Barang ini, yang dilakukan oleh , </td>
                        </tr>
                    </table>
                    <br>
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 50%">
                                <table>
                                    <tr>
                                        <td colspan="3" width="50px" style="font-weight:bold;">Pihak Pertama</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Nama
                                        </td>
                                        <td>:</td>
                                        <td>{{$data->approvals->where('type','Barang Telah diserahkan Pengelola Gudang')->first()->user->name ?? 0}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Jabatan
                                        </td>
                                        <td>:</td>
                                        <td>Pengelola Gudang</td>
                                    </tr>
                                </table>
                            </td style="width: 50%">
                            <td>
                                <table>
                                    <tr>
                                        <td colspan="3" width="50px" style="font-weight:bold;">Pihak Kedua</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Nama
                                        </td>
                                        <td>:</td>
                                        <td>{{$data->approvals->where('type','Barang Telah diterima')->first()->user->name ??''}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Jabatan
                                        </td>
                                        <td>:</td>
                                        <td>{{$data->approvals->where('type','Barang Telah diterima')->first()->user->role->name ?? ''}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>


                    </table>
                </div>

                <div style="height: 400px;text-align:center;width:100%">
                    <table class="table" >
                            <thead>
                                <tr class="tr-head"
                                    style="background: #1E1E1E;color:white;font-weight:bold;">
                                    <td class="" style="vertical-align: middle;" width="1" rowspan="2">
                                        No</td>
                                    <td class="td-head" style="vertical-align: middle" rowspan="2">
                                        Nama Barang</td>
                                    <td class="td-head" style="vertical-align: middle" rowspan="2">
                                        Kode</td>
                                    <td class="td-head" style="vertical-align: middle" rowspan="2">
                                        Kategori</td>
                                    <td class="td-head text-center" colspan="2">Jumlah</td>
                                    <td class="td-head" rowspan="2">Satuan</td>

                                </tr>
                                <tr class="tr-head"
                                    style="background: #1E1E1E;color:white;font-weight:bold;">
                                    <td class="td-head" style="border-radius: 0px !important">
                                        Permintaan</td>
                                    <td class="td-head" style="border-radius: 0px !important">
                                        Disetujui</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $jumlah = 0;
                                    $jumlah_disetujui = 0;
                                @endphp
                                @foreach ($data->barang_diminta as $item)
                                @php
                                    $jumlah +=$item->jumlah;
                                    $jumlah_disetujui +=$item->jumlah_disetujui;
                                @endphp
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td style="width: 265px">{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach
                                {{-- <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="white-space: nowrap"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="white-space: nowrap"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="white-space: nowrap"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr> --}}
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="white-space: nowrap">Jumlah Barang</td>
                                    <td>{{$jumlah}}</td>
                                    <td>{{$jumlah_disetujui}}</td>
                                    <td></td>
                                </tr>
                                {{-- @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td style="width: 265px">{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach
                                @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td>{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach
                                @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td>{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach
                                @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td>{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach
                                @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td>{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach
                                @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td>{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach

                                 @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td>{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach
                                 @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td>{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach

                                 @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td>{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach

                                 @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td>{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach

                                 @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td>{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach

                                 @foreach ($data->barang_diminta as $item)
                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td>{{$item->barang->nama_barang ?? null}}</td>
                                        <td>{{$item->barang->kode_barang ?? null}}</td>
                                        <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                        <td>{{$item->jumlah ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                    </tr>
                                @endforeach --}}


                            </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="footer">
            <table style="">
                <tr>

                    <td style="width: auto;padding-right:10px;" width="auto">
                        <div style="text-align: center;display: block;">
                            <div style="margin-bottom:18px;">
                                <span style="font-size: 12px;">Dikeluarkan Oleh</span>
                            </div>
                            @if ($data->approvals->where('type','Barang Telah diserahkan Pengelola Gudang')->first())
                                {!! '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG(route('public-data.user',$data->approvals->where('type','Barang Telah diserahkan Pengelola Gudang')->first()->approve_by_id ?? 0), 'QRCODE',2.5,2.5) . '" alt="barcode"   />' !!}
                                <span style="display: block;font-size: 12px;">Pengelola Gudang</span>
                                <span style="display: block;font-size: 10px;" >{{$data->approvals->where('type','Barang Telah diserahkan Pengelola Gudang')->first()->timestamp ?? '-'}}</span>
                            @else
                                <img src="{{asset('images/icon/nay.png')}}" height="72" alt="">
                                {{-- {!! '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG(route('public-data.user',$data->user_id ?? 0), 'QRCODE',2.5,2.5) . '" alt="barcode"   />' !!} --}}
                                <span style="display: block;font-size: 12px;">Pengelola Gudang</span>
                                <span style="display: block;font-size: 10px;" >{{$data->tanggal_permintaan ?? '-'}}</span>
                            @endif

                        </div>
                    </td>
                    <td style="width: auto;padding-right:10px;">
                        <div style="text-align: center;">
                            <div style="margin-bottom:18px;display: block;">
                                <span style="font-size: 12px;">Diterima Oleh</span>
                            </div>
                             @if ($data->approvals->where('type','Barang Telah diterima')->first())
                                {!! '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG(route('public-data.user',$data->approvals->where('type','Barang Telah diterima')->first()->approve_by_id ?? 0), 'QRCODE',2.5,2.5) . '" alt="barcode"   />' !!}
                                <span style="display: block;font-size: 12px;">{{$data->approvals->where('type','Barang Telah diterima')->first()->user->name ?? 'N/A'}}</span>
                                <span style="display: block;font-size: 10px;">{{$data->approvals->where('type','Barang Telah diterima')->first()->timestamp ?? '-'}}</span>
                            @else
                                <img src="{{asset('images/icon/nay.png')}}" height="72" alt="">
                                {{-- {!! '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG(route('public-data.user',$data->user_id ?? 0), 'QRCODE',2.5,2.5) . '" alt="barcode"   />' !!} --}}
                                <span style="display: block;font-size: 12px;">{{$data->user->name}}</span>
                                <span style="display: block;font-size: 10px;" >{{$data->tanggal_permintaan ?? '-'}}</span>
                            @endif
                        </div>
                    </td>

                </tr>
            </table>


        </div>
    </div>





 <script type="text/php">
        if ( isset($pdf) ) {
            // OLD
            // $font = Font_Metrics::get_font("helvetica", "bold");
            // $pdf->page_text(72, 18, "{PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(255,0,0));
            // v.0.7.0 and greater
            $x = 490;
            $y = 810;
            $text = "Lembar {PAGE_NUM} dari {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "bold");
            $size = 6;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
</body>

</html>
