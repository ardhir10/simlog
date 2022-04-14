@extends('main')


@section('content')
<div class="page-content">

<!-- end page title -->
<div class="row animate__animated  animate__fadeIn">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header justify-content-between d-flex align-items-center">
                <h4 class="card-title">{{$page_title}}</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('master-data.sub-kategori-barang.update',$data->id) }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for=""><strong>Kategori Utama</strong></label>
                                    <select name="parent_id" class="form-select" id="">
                                        @foreach ($parent as $item)
                                            <option {{$item->id == $data->parent_id ? 'selected=selected':''}} value="{{$item->id}}">{{$item->nama_kategori}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for=""><strong>Name Kategori</strong></label>
                                    <input type="text" name="nama_kategori" value="{{$data->nama_kategori}}" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for=""><strong>Kode Kategori</strong></label>
                                    <input type="text" name="kode_kategori" value="{{$data->kode_kategori}}" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for=""><strong>Keterangan</strong></label>
                                    <textarea class="form-control" name="keterangan" id="" cols="30" rows="3">{{$data->keterangan}}</textarea>
                                </div>
                            </div>
                        </div>

                    <div class="mt-2">
                        <button class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>
                            Save</button>
                        <a href="{{route('master-data.sub-kategori-barang.index')}}" class="btn btn-sm btn-danger"><i
                                class="fa fa-arrow-left"></i> Back</a>
                    </div>
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
    $('#data-table').DataTable({});
</script>
@endpush
