

@extends('main')

@push('styles')
<style>
    .note-insert{
        display: none;
    }
</style>

@endpush
@section('content')
<div class="page-content">

    <!-- end page title -->
    <div class="row animate__animated  animate__fadeIn">
        <div class="col-lg-12">
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
                    <form action="{{ route('barang-persediaan.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label for=""><strong>Sumber Barang</strong></label>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check ">
                                                <input class="form-check-input sumber_barang" type="radio" name="sumber_barang" id="flexRadioDefault1" {{Request::get('sumber_barang') == 'existing' ? 'checked=checked':''}} data-url={{route('barang-persediaan.create',['sumber_barang'=>'existing'])}} value="existing">
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    Barang Existing
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check ">
                                                <input class="form-check-input sumber_barang" type="radio" name="sumber_barang" id="flexRadioDefault2" {{Request::get('sumber_barang') == 'baru' ? 'checked=checked':''}} data-url={{route('barang-persediaan.create',['sumber_barang'=>'baru'])}} value="baru">
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    Barang Baru
                                                </label>
                                            </div>
                                        </div>
                                    </div>




                                </div>
                            </div>

                        </div>
                        @if (Request::get('sumber_barang') == 'existing')
                            @include('barang-persediaan.create-existing')
                             <div class="mt-2">
                                <button class="btn btn btn-success">
                                    <i class="fa fa-save"></i>
                                    Simpan</button>
                                <a href="{{route('barang-persediaan.index')}}" class="btn btn btn-danger"><i
                                        class="fa fa-arrow-left"></i>
                                    Kembali</a>
                            </div>
                        @elseif(Request::get('sumber_barang') == 'baru')
                            @include('barang-persediaan.create-baru')
                             <div class="mt-2">
                            <button class="btn btn btn-success">
                                    <i class="fa fa-save"></i>
                                    Simpan</button>
                                <a href="{{route('barang-persediaan.index')}}" class="btn btn btn-danger"><i
                                        class="fa fa-arrow-left"></i>
                                    Kembali</a>
                            </div>
                        @endif




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
<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').removeClass('d-none');
        $('#summernote').summernote({
            height: 200
        });
    });
     $(function(){
      // bind change event to select
      $('input:radio[name="sumber_barang"]').on('change', function () {
          var url = $('input[name="sumber_barang"]:checked').attr("data-url");

          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
    });

</script>
@endpush

