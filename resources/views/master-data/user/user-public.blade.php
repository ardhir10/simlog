@extends('main-public')


@section('content')
<div class="page-content">
    <div class="row animate__animated  animate__fadeIn">
        <div class="col-lg-10">
            <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <h4 class="card-title">Simoli - User Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 text-center">
                                    <img style="border-radius: 10px;" height="170px" src="{{asset('images/avatar/'.optional($user)->fs_avatar)}}" alt="">
                            </div>
                            <div class="col-lg-10">
                                 <div class="card">
                            <div class="card-header text-center">
                                <span class="d-block fs-4 text-muted fw-bold">{{$user->eksternal_role_name??optional(optional($user)->role)->name}}</span>
                                <span>{{$user->eksternal_company_name}}</span>
                            </div>
                            <div class="card-body">

                                <div class="row mb-4">
                                    <div class="col-lg-6 mb-3">
                                        <span class="d-block text-setup fs-4 fw-bold">Nama Lengkap</span>
                                        <span class=" fs-5">{{$user->name}}</span>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <span class="d-block text-setup fs-4 fw-bold">NIP</span>
                                        <span class=" fs-5">{{$user->nip}}</span>
                                    </div>

                                </div>
                                <div class="row mb-4">
                                    <div class="col-lg-6 mb-3">
                                        <span class="d-block text-setup fs-4 fw-bold">Username</span>
                                        <span class=" fs-5">{{$user->username}}</span>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <span class="d-block text-setup fs-4 fw-bold">Email</span>
                                        <span class=" fs-5">{{$user->email}}</span>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <span class="d-block text-setup fs-4 fw-bold">Telepon</span>
                                        <span class=" fs-5">{{$user->nomor_telepon}}</span>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <span class="d-block text-setup fs-4 fw-bold">Jabatan</span>
                                        <span class=" fs-5">{{$user->jabatan}}</span>
                                    </div>

                                </div>

                            </div>
                        </div>
                            </div>

                        </div>


                    </div>
                </div>





            </form>
        </div>
    </div>

    <!-- end row -->

</div> <!-- container-fluid -->
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush
