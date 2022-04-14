@extends('main')


@section('content')
<div class="page-content">
    <div class="row animate__animated  animate__fadeIn">
        <div class="col-lg-12">
            <form action="{{ route('user.update',$data->id) }}" method="post" enctype="multipart/form-data">
                @csrf
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
                        <div class="form-group d-flex">
                            <div class="row ms-1">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="" class="fw-bolder">User Role :</label>
                                        <select name="role_id" id="role_id" class="form-select select2" required>
                                            <option value="">---</option>
                                            @foreach ($roles as $item)
                                                <option {{Request::get('role') == $item->name ? 'selected=selected':''}} data-url='{{route('user.edit',['id'=>$data->id,'role'=>$item->name])}}' value="{{$item->id}}" >{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (Request::get('role') == 'Nakhoda')
                    @include('master-data.user.edit-nakhoda')
                @elseif(Request::get('role') == 'Kepala SROP')
                    @include('master-data.user.edit-kepala-srop')
                @elseif(Request::get('role') == 'Kepala VTS')
                    @include('master-data.user.edit-manager-vts')
                @else
                    @include('master-data.user.edit-general')
                @endif




            </form>
        </div>
    </div>

    <!-- end row -->

</div> <!-- container-fluid -->
</div>
@endsection

@push('scripts')
<script>
    $('#data-table').DataTable({});
    $(".select2").select2();

    let typeUser = @JSON(old('crType'));
    if (typeUser === 'typeInternalUser') {
        $('#generalForm').removeClass('d-none');
        $('#formInternalUser').removeClass('d-none');
        $('#formBadanUsahaPemilikUser').addClass('d-none');
    } else if(typeUser === 'typeBadanUsahaPemilik') {
        $('#generalForm').removeClass('d-none');
        $('#formBadanUsahaPemilikUser').removeClass('d-none');
        $('#formInternalUser').addClass('d-none');
    }

    $('input[name=crType]').change(function () {
        $('#generalForm').removeClass('d-none');
        var value = $('input[name=crType]:checked').val();
        if (value == 'typeInternalUser') {
            $('#formInternalUser').removeClass('d-none');
            $('#formBadanUsahaPemilikUser').addClass('d-none');
        } else {
            $('#formBadanUsahaPemilikUser').removeClass('d-none');
            $('#formInternalUser').addClass('d-none');
        }
    });

     $(function(){
      // bind change event to select
      $('#role_id').on('change', function () {
          var url = $(this).find(':selected').data('url'); // get selected value
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
    });

</script>
@endpush
