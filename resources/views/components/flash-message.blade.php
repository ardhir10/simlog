@push('styles')
    <link href="{{asset('/assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
{{-- @if(session()->has('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    {!! session()->get('success') !!}
</div>
@endif --}}

@if(session()->has('info'))
<div class="alert alert-info alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    {!! session()->get('info') !!}
</div>
@endif

@if(session()->has('warning'))
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    {!! session()->get('warning') !!}
</div>
@endif

@if(session()->has('failed'))
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    {!! session()->get('failed') !!}
</div>
@endif

@push('scripts')
    <!-- Sweet Alerts js -->
    <script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
    @if(session()->has('success'))
    <script>
         Swal.fire({
            title: "Success !",
            text: "{!! session()->get('success') !!}",
            icon: "success",
            // showCancelButton: !0,
            timer: 3000

            // confirmButtonColor: "#038edc",
            // cancelButtonColor: "#f34e4e"
        })
    </script>
    @endif
    @if(session()->has('failed'))
    <script>
         Swal.fire({
            title: "Information !",
            text: "{!! session()->get('failed') !!}",
            icon: "warning",
            // showCancelButton: !0,
            timer: 3000

            // confirmButtonColor: "#038edc",
            // cancelButtonColor: "#f34e4e"
        })
    </script>
    @endif

@endpush
