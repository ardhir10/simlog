@extends('main')


@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        {{-- <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">{{$page_title}}</h4>
    </div>
</div>
</div> --}}
<!-- end page title -->
<div class="row">
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
                <form action="{{route('user.update-profile',$user->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for=""><strong>Nama Lengkap</strong></label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="{{$user->name}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for=""><strong>Email</strong></label>
                        <input type="text" name="email" class="form-control" placeholder="Email" value="{{$user->email}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for=""><strong>username</strong></label>
                        <input type="text" name="username" class="form-control" placeholder="username" value="{{$user->username}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for=""><strong>Ganti Password</strong></label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <select name="role_id" class="form-control form-control-sm" id="" disabled>
                            <option value="">--SELECT ROLE </option>
                            @foreach ($roles as $d)
                                <option {{$user->role_id == $d->id ? 'selected=selected' :''}} value="{{$d->id}}">{{$d->name}}</option>


                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Dokter</label>
                        <select name="fs_kd_peg" class="form-control form-control-sm" id="" disabled>
                            <option value="">--SELECT DOKTER </option>
                            @foreach ($dokter as $d)
                                <option {{$user->fs_kd_peg == $d->fs_kd_dokter ? 'selected=selected' :''}} value="{{$d->fs_kd_dokter}}">{{$d->fs_dokter}}</option>

                            @endforeach
                        </select>
                    </div>
                     <div class="form-group">
                        <img src="{{asset('images/avatar/'.$user->fs_avatar)}}" width="70" alt="" style="">
                        <br>
                        <label for="">Upload Profil</label>
                        <input type="file" name="avatar" class="form-control-sm">
                    </div>

                    <div class="mt-2">
                        <button class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>
                            Save</button>
                        <a href="{{route('user.index')}}" class="btn btn-sm btn-danger"><i
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
