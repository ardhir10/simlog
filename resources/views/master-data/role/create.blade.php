@extends('main')


@section('content')
<div class="page-content">

    <!-- end page title -->
    <div class="row animate__animated  animate__fadeIn">
        <div class="col-lg-4">
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
                    <form action="{{ route('role.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for=""><strong>Name</strong></label>
                            <input type="text" name="name" class="form-control" placeholder="Role name">
                        </div>
                        {{-- <div class="form-group">
                        <label for=""><strong>Permissions</strong></label>
                        <select name="permissions_old[]" class="form-control" multiple id="">
                            @foreach ($permissions as $p)
                                <option value="{{$p->id}}">{{$p->name}}</option>
                        @endforeach
                        </select>
                </div> --}}

                <div class="form-group">
                    <label for="">Permissions</label>
                    <br>
                    @foreach ($permissions as $p)
                    <div style="display:block">
                        <input name="permissions[]" type="checkbox" value="{{$p->id}}">
                        <small>{{$p->name}}</small>
                    </div>
                    @endforeach
                </div>

                <div class="mt-2">
                    <button class="btn btn-sm btn-success">
                        <i class="fa fa-save"></i>
                        Save</button>
                    <a href="{{route('role.index')}}" class="btn btn-sm btn-danger"><i class="fa fa-arrow-left"></i>
                        Back</a>
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
