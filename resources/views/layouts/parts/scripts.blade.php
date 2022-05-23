@if ($message = Session::get('success'))
    <script defer>
        //success message
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: false,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'success',
            title: '{{$message}}'
        })
    </script>
@endif

@if (Session::has('error'))
    <script defer>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: '{{ Session::get('error') }}',
        })
    </script>
@endif

<script>
    //delete confirm
    {{--console.log(1)--}}
    {{--$('.confirm').click(function(event) {--}}
    {{--    var form =  $(this).closest("form");--}}
    {{--    var name = $(this).data("delete-confirm");--}}
    {{--    event.preventDefault();--}}
    {{--    Swal.fire({--}}
    {{--        title: "{{__('Are you sure you want to delete this record?')}}",--}}
    {{--        text: "{{__('If you delete this, it will be gone forever.')}}",--}}
    {{--        icon: "warning",--}}
    {{--        showCancelButton: true,--}}
    {{--        confirmButtonText: "{{__("Yes, delete it!")}}",--}}
    {{--        cancelButtonText: "{{__('No, cancel!')}}",--}}
    {{--        reverseButtons: false--}}
    {{--    })--}}
    {{--        .then((e) => {--}}
    {{--            if (e.value) {--}}
    {{--                form.submit();--}}
    {{--            }--}}
    {{--        });--}}
    {{--});--}}
</script>
