<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Blade;

trait DataTableDefaults
{
    public function dataTableDefaultDrawCallBack(): string
    {
        return Blade::render('
        function() {
            $(\'.confirm\').click(function(event) {
                var form =  $(this).closest("form");
                var name = $(this).data("delete-confirm");
                event.preventDefault();
                Swal.fire({
                    title: "{{__(\'Are you sure you want to delete this record?\')}}",
                    text: "{{__(\'If you delete this, it will be gone forever.\')}}",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "{{__("Yes, delete it!")}}",
                    cancelButtonText: "{{__(\'No, cancel!\')}}",
                    reverseButtons: false
                })
                    .then((e) => {
                        if (e.value) {
                            form.submit();
                        }
                    });
                });
            }'
        );
    }

    public function dataTableDefaultParameters(): array
    {
        return [
            'fnDrawCallback' => $this->dataTableDefaultDrawCallBack(),
            'stateSave' => true,
            'paging' => true,
            "lengthMenu" => [[10, 50, 100, 500], [10, 50, 100, 500]],
            'order' => [[0, "desc"]],
        ];
    }
}
