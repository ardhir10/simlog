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
    <div class="col-lg-12">
        <div class="card">

            <div class="card-header ">
                {{-- <h4 class="card-title">{{$page_title}}</h4> --}}
                <div class="row">
                    <div class="col-12">
                        @include('components.flash-message')
                    </div>
                    <div class="col-lg-2">
                            <img class="shadow-lg" style="height:auto;width:100%;max-height:200px;border-radius:15px;" src="{{asset('images/avatar/'.Auth::user()->fs_avatar)}}" alt="">
                    </div>
                    <div class="col-lg-7" style="margin: auto 19px;">
                        <h1 class="text-setup fw-bolder" style="color: #0BB97A;font-size:7vh;">{{Auth::user()->name}}</h1>
                        <h2 class="text-setup fw-bolder" style="color: #0BB97A;font-size:5vh">{{Auth::user()->role->name ?? ''}}</h2>
                    </div>


                </div>
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
                <div class="row">
                    <div class="col-lg-4">
                        <form action="{{route('user.setting.update')}}" method="post" class="p-4">
                            @csrf
                            <div class="card p-2" style="box-shadow: 0 8px 17px 0 rgba(0, 0, 0, .2), 0 6px 20px 0 rgba(0, 0, 0, .15);">

                                <div class="card-body ">
                                    <div class="form-group">
                                        <span class="d-block fs-4 fw-bold">Ubah Password</span>
                                        <hr>
                                        <label for=""><strong>Password Baru</strong></label>
                                        <input type="password" name="password" class="form-control" placeholder="password">
                                    </div>
                                    <button class="btn btn-success w-100 mt-2">Simpan</button>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="col-lg-8">
                        <div class="p-4">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <span class="fs-5 d-block">Nama Lengkap</span>
                                    <span class="fs-4 fw-bolder">{{Auth::user()->name ?? 'N/A'}}</span>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <span class="fs-5 d-block">NIP (Nomor Induk Pegawai)</span>
                                    <span class="fs-4 fw-bolder">{{Auth::user()->nip ?? 'N/A'}}</span>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <span class="fs-5 d-block">Jabatan</span>
                                    <span class="fs-4 fw-bolder">{{Auth::user()->jabatan ?? 'N/A'}}</span>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <span class="fs-5 d-block">Email</span>
                                    <span class="fs-4 fw-bolder">{{Auth::user()->email ?? 'N/A'}}</span>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <span class="fs-5 d-block">Nomor Telepon</span>
                                    <span class="fs-4 fw-bolder">{{Auth::user()->nomor_telepon ?? 'N/A'}}</span>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>



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
