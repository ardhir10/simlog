<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$data->nomor_rab}} (RAB)</title>
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
                    <img src="https://simlog.disnavpriok.id/assets/images/icon/kemenhub.png" height="130px" alt=""></td>
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
                    <span style="font-weight:bold;display:block;">Rencana Anggaran Biaya (RAB)</span>
                    <span style="font-weight:bold;">{{$data->nomor_rab}} | {{$data->timestamp}}</span>

                </div>
            </div>
            <div class="middle">
                <div>
                    <table style="width: 100%">
                        <tr>
                            <td width="50px">Kegiatan</td>
                            <td style="width: 1%">:</td>
                            <td> {{$data->kegiatan ?? null}}</td>
                        </tr>
                        <tr>
                            <td width="50px">Pengguna</td>
                            <td style="width: 1%">:</td>
                            <td> {{$data->pengguna() ?? null}}</td>
                        </tr>
                        <tr>
                            <td width="50px">MAK</td>
                            <td style="width: 1%">:</td>
                            <td> {{$data->mak ?? null}}</td>
                        </tr>
                        <tr>
                            <td width="50px">Tahun Anggaran </td>
                            <td style="width: 1%">:</td>
                            <td> {{$data->tahun_anggaran}}</td>
                        </tr>
                    </table>
                </div>

                <div style="height: 400px;text-align:center;width:100%">
                    <table class="table" >
                            <thead>
                                <tr class="tr-head"
                                    style="background: #ebebeb;color:black;font-weight:bold;">
                                    <td class="" style="vertical-align: middle;" width="1">
                                        No</td>
                                    <td class="td-head" style="vertical-align: middle" >
                                        Nama Barang</td>
                                    <td class="td-head" style="vertical-align: middle" >
                                        Satuan</td>
                                    <td class="td-head" style="vertical-align: middle" >
                                        Harga Satuan</td>
                                    <td class="td-head text-center" >QTY</td>
                                    <td class="td-head text-center" >Sub Total</td>
                                    <td class="td-head">Keterangan</td>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $jumlah = 0;
                                    $jumlah_disetujui = 0;
                                    $subTotal = 0;
                                @endphp
                                @foreach ($data->barang_diminta as $item)
                                    @php
                                        $jumlah +=$item->qty;
                                        $jumlah_disetujui +=$item->jumlah_disetujui;
                                        $subTotal += $item->jumlah_disetujui * $item->harga_satuan;
                                    @endphp

                                    <tr>
                                        <td style="width: 20px">{{$loop->iteration}}</td>
                                        <td style="width: 265px">{{$item->nama_barang ?? null}}</td>
                                        <td>{{$item->satuan ?? null}}</td>
                                        <td>{{$item->harga_satuan ?? null}}</td>
                                        <td>{{$item->jumlah_disetujui ?? null}}</td>
                                        <td>{{$item->mata_uang}}&nbsp;{{$item->jumlah_disetujui * $item->harga_satuan ?? null}}</td>
                                        <td>{{$item->keterangan ?? null}}</td>
                                    </tr>
                                @endforeach
                                <tr style="background: #ebebeb;">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="white-space: nowrap">Jumlah</td>
                                    <td>{{$item->mata_uang}}&nbsp;{{$subTotal}}</td>
                                    <td></td>
                                </tr>

                                @php
                                    $ppn = $subTotal*0.11;
                                @endphp
                                <tr style="background: #ebebeb;">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="white-space: nowrap">PPn 11%</td>
                                    <td>{{$item->mata_uang}}&nbsp;{{$ppn}}</td>
                                    <td></td>
                                </tr>
                                <tr style="background: #ebebeb;">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="white-space: nowrap">TOTAL</td>
                                    <td>{{$item->mata_uang}}&nbsp;{{$subTotal+$ppn}}</td>
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


                    <!-- <td style="width: auto;padding-right:10px;" class="d-none">
                        <div style="text-align: center;">
                            <div style="margin-bottom:18px;">
                                <span style="display: block;font-size: 12px;">Diverifikasi Oleh</span>
                            </div>
                            @if ($data->approvals->where('type','Disetujui Kasie Pengadaan')->first())
                                {!! '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG(route('public-data.user',$data->approvals->where('type','Disetujui Kasie Pengadaan')->first()->approve_by_id ?? 0), 'QRCODE',2.5,2.5) . '" alt="barcode"   />' !!}
                                <span style="display: block;font-size: 12px;">Kasie Pengadaan</span>
                                <span style="display: block;font-size: 10px;" >{{$data->approvals->where('type','Disetujui Kasie Pengadaan')->first()->timestamp ?? '-'}}</span>
                            @else
                                <img src="{{asset('images/icon/nay.png')}}" height="72" alt="">

                                {{-- {!! '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG(route('public-data.user',$data->user_id ?? 0), 'QRCODE',2.5,2.5) . '" alt="barcode"   />' !!} --}}
                                <span style="display: block;font-size: 12px;">Kasie Pengadaan</span>
                                <span style="display: block;font-size: 10px;" >{{$data->tanggal_permintaan ?? '-'}}</span>
                            @endif
                        </div>
                    </td> -->
                    <td style="width: auto;padding-right:10px;">
                        <div style="text-align: center;">
                            <div style="margin-bottom:18px;">
                                <span style="display: block;font-size: 12px;">Disetujui Oleh</span>
                            </div>
                            @if ($data->approvals->where('type','Disetujui Kabid Logistik')->first())
                                {!! '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG(route('public-data.user',$data->approvals->where('type','Disetujui Kabid Logistik')->first()->approve_by_id ?? 0), 'QRCODE',2.5,2.5) . '" alt="barcode"   />' !!}
                                <span style="display: block;font-size: 12px;">Kepala Bidang Logistik</span>
                                <span style="display: block;font-size: 10px;" >{{$data->approvals->where('type','Disetujui Kabid Logistik')->first()->timestamp ?? '-'}}</span>
                            @else
                                <img src="{{asset('images/icon/nay.png')}}" height="72" alt="">

                                {{-- {!! '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG(route('public-data.user',$data->user_id ?? 0), 'QRCODE',2.5,2.5) . '" alt="barcode"   />' !!} --}}
                                <span style="display: block;font-size: 12px;">Kepala Bidang Logistik</span>
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
