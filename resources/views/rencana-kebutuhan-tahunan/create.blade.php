


@extends('main')


@push('styles')

<style>


</style>

@endpush
@section('content')
<div class="page-content">

<!-- end page title -->
<div class="row animate__animated  animate__fadeIn">
    <div class="col-lg-12 ">
        <div class="card shadow-lg">
             <div class="card-header justify-content-between d-flex align-items-center">
                <h4 class="card-title">{{$page_title}}</h4>
            </div>
            <div class="card-body">
                <div class="col-12">
                    @include('components.flash-message')
                </div>
                <form action="{{route('rencana-kebutuhan-tahunan.store',$data->id ?? 0)}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Barang Persediaan</label>
                                <select name="nama_barang" id="" class="form-select select2">
                                    @foreach ($barang_persediaan as $barang_persediaan)
                                        <option value="{{$barang_persediaan->nama_barang}}">{{$barang_persediaan->nama_barang}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Tahun</label>
                                    <select name="tahun" id="" required class="form-select select2">
                                    <?php
                                    for ($x=date("Y"); $x>1900; $x--)
                                    {
                                            if (($data->tahun_anggaran ?? null ) == $x) {
                                                echo'<option selected=selected value="'.$x.'">'.$x.'</option>';
                                            } else {
                                                echo'<option value="'.$x.'">'.$x.'</option>';
                                            }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">UMUM</label>
                                <input type="number" name="p01" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">SIE KEPEG & UMUM</label>
                                <input type="number" name="p02" class="form-control">
                            </div>
                        </div>
                         <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">SIE KEUANGAN</label>
                                <input type="number" name="p03" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">PENGADAAN</label>
                                <input type="number" name="p04" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">INVENTARIS</label>
                                <input type="number" name="p05" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">SIE SARPRAS</label>
                                <input type="number" name="p06" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">PROGRAM</label>
                                <input type="number" name="p07" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">SBNP</label>
                                <input type="number" name="p08" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">TELKOMPEL</label>
                                <input type="number" name="p09" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">PENGLA</label>
                                <input type="number" name="p10" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">KNK</label>
                                <input type="number" name="p11" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <label for="">BENGKEL</label>
                                <input type="number" name="p12" class="form-control">
                            </div>
                        </div>



                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- end row -->

</div> <!-- container-fluid -->
</div>
@endsection

@push('scripts')
<script>
    $('#data-table').DataTable({
        //   "pageLength": 3

    });

</script>
@endpush
