@extends('main')


@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-lg-12">
                <div class="card animate__animated  animate__fadeIn">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <h4 class=" fw-bolder">User Management</h4>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            @include('components.flash-message')
                        </div>
                        <div class="mb-2 wd-100p ">
                            {{-- <a class="btn  {{Request::get('type') == '' ? 'btn-primary':'btn-outline-primary'}}"
                                href="{{route('user.index')}}">All</a>
                            <a class="btn  {{Request::get('type') == 'internal' ? 'btn-primary' : 'btn-outline-primary'}}"
                                href="{{route('user.index',['type'=>'internal'])}}">Internal</a>
                            <a class="btn  {{Request::get('type') == 'eksternal' ? 'btn-primary' : 'btn-outline-primary'}}"
                                href="{{route('user.index',['type'=>'eksternal'])}}">Eksternal</a> --}}
                            <a href="{{route('user.create')}}" class="btn-outline btn-outline-success btn mb-2 float-end fw-bold"> Create Account <i class="fa fa-plus"></i></a>
                        </div>

                        <table class="table-striped datatables" id="data-table" style="font-size: 16px">
                            <thead style="border-radius: 10px;">
                                <tr class="tr-head bg-simlog" style="border-radius: 10px;">
                                    <th  style="border-top-left-radius: 10px;border-bottom-left-radius: 10px;"width="1%" >#</th>
                                    <th  >ROLE</th>
                                    <th  >FULL NAME </th>
                                    <th  >NIP </th>
                                    <th  >PHONE NUMBER </th>
                                    <th  >EMAIL </th>
                                    <th  style="border-top-right-radius: 10px;border-bottom-right-radius: 10px;"class="td-head"  width="10%">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$user->role->name}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->nip}}</td>
                                    <td>{{$user->nomor_telepon}}</td>
                                        <td>{{$user->email}}</td>
                                    <td>
                                        <div class="d-flex">

                                            <a href="{{route('user.edit',['id'=>$user->id,'role'=>$user->role->name])}}"
                                                class="btn btn rounded-3  btn-info me-1"
                                                data-toggle="tooltip" title="Edit Data">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if (Auth::user()->id != $user->id)
                                            <button class="btn btn rounded-3  btn-danger"
                                                data-toggle="tooltip" title="Hapus Data"
                                                onclick="return confirmDelete('{{route('user.delete',$user->id)}}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            @else
                                            <button class="btn btn rounded-3  btn-danger"
                                                data-toggle="tooltip" title="Hapus Data" disabled>
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- <table class="table-bordered table-striped datatables" id="data-table" style="font-size: 16px">
                    <thead>
                        <tr class="tr-head bg-dark">
                            <th width="1%">No</th>
                            <th>Pic</th>
                            <th width="">Nama </th>
                            <th>Email </th>
                            <th>Username </th>
                            <th class="td-head" width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody >
                        @foreach ($users as $user)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                        <td>
                            <img src="{{asset('images/avatar/'.$user->fs_avatar)}}" width="30" alt="" style="">
                        </td>
                        <td>
                            {{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->username}}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{route('user.edit',$user->id)}}"
                                    class="btn btn rounded-3 btn-outline btn-outline-success me-1" data-toggle="tooltip"
                                    title="Edit Data">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @if (Auth::user()->id != $user->id)
                                <button class="btn btn rounded-3 btn-outline btn-outline-danger" data-toggle="tooltip"
                                    title="Hapus Data"
                                    onclick="return confirmDelete('{{route('user.delete',$user->id)}}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @else
                                <button class="btn btn rounded-3 btn-outline btn-outline-danger" data-toggle="tooltip"
                                    title="Hapus Data" disabled>
                                    <i class="fa fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                        </tr>
                        @endforeach




                        </tbody>

                        </table> --}}
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
