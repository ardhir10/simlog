
<style>
.bg-ditolak{
    background-color: #ED1F24 !important;
}

.bg-diproses{
    background-color: #F3EC17 !important;
}
.bg-disetujui{
    background-color: #70BF44 !important;
}
.bg-selesai{
    background-color: #050505 !important;
}
.avatar-sm{
    height: 1.2rem !important;
    width: 1.2rem !important;
}
.clickable-row{
    cursor: pointer;
}
.clickable-row:hover{
    background-color: #0bb9795b !important;
}

table{
    /* width: 100%; */
      border: 1px solid;
      border-collapse: collapse;

  border: 0.5px solid black;

}
table, td, th {
  border: 0.5px solid black;
  padding: 5px;
}
table,thead tr th {
  background-color: #92D050;
  color: black;
  font-weight: bold;
}
table,tbody td {
  /* background-color: transparent; */
  color: black;
  font-weight: normal;
    font-size: 10.5px;
    padding: 1px 5px  !important;
}

table th{
    font-size: 11px;
    border: 0.5px solid black !important;
        vertical-align: top;
    text-align: center;
}
table thead {
  position: sticky;
  top: 0; /* Don't forget this, required for the stickiness */
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
}

tr:nth-child(even) {
  background-color-color: lightgray;
}

</style>
<table >
    <thead>
        <tr >
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid" rowspan="2">NO</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid" rowspan="2">KODE</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid" rowspan="2">URAIAN</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid" rowspan="2">TAHUN</th>


            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid" colspan="3" >NILAI</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid" colspan="2" >SALDO AKHIR</th>


            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid" rowspan="2">POSISI BARANG</th>

            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid" colspan="12" >PERUNTUKKAN</th>
        </tr>
        <tr style="background-color: #92D050">
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">SATUAN</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">HARGA</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">MATA UANG</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">STOCK</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">JUMLAH TOTAL</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">UMUM</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">SBNP</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">TELKOMPEL</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">PENGLA</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">KNK</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">BENGKEL</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">SIE KEPEG DAN UMUM</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">SIE KEUANGAN</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">PENGADAAN</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">INVENTARIS</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">SIE SARPRAS</th>
            <th style="background-color: #92D050;text-align:center;vertical-align: top;border:1px solid">PROGRAM</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laporans as $item)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$item['kode_barang']}}</td>
                <td>{{$item['nama_barang']}}</td>
                <td>{{$item['tahun_perolehan']}}</td>
                <td>{{$item['satuan']}}</td>
                <td>{{$item['harga_perolehan']}}</td>
                <td>{{$item['mata_uang']}}</td>
                <td>{{$item['stock']}}</td>
                <td>{{$item['jumlah_total']}}</td>
                <td>{{$item['posisi_barang']}}</td>

                <td style="{{ $item['umum'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['umum']}}</td>
                <td style="{{ $item['sbnp'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['sbnp']}}</td>
                <td style="{{ $item['telkompel'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['telkompel']}}</td>
                <td style="{{ $item['pengla'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['pengla']}}</td>
                <td style="{{ $item['knk'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['knk']}}</td>
                <td style="{{ $item['bengkel'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['bengkel']}}</td>

                <td style="{{ $item['siekepeg'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['siekepeg']}}</td>
                <td style="{{ $item['siekeuangan'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['siekeuangan']}}</td>
                <td style="{{ $item['siepengadaan'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['siepengadaan']}}</td>
                <td style="{{ $item['sieinventaris'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['sieinventaris']}}</td>
                <td style="{{ $item['sarpras'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['sarpras']}}</td>
                <td style="{{ $item['sieprogram'] > 0 ? 'color:black;font-weight:bolder;':''}}">{{$item['sieprogram']}}</td>



            </tr>
        @endforeach
    </tbody>
</table>
