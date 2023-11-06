@extends('admin.index')

@section('content')
    <div class="page-container">
        <div class="page-content-wrapper">
            <div class="content">
                <div class="container-fluid p-l-25 p-r-25 p-t-0 p-b-25 sm-padding-10">
                    <section class="content-header mt-3 mb-3">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h1>1111</h1>
                                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary ml-4">{{ __('New Post') }}</a>
                                </div>
                            </div>
                        </div><!-- /.container-fluid -->
                    </section>
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="postDataTable" class="table table-bordered table-striped mt-4 mb-2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Categoey</th>
                                        <th>Creator</th>
                                        <th>Pay</th>
                                        <th>Status</th>
                                        <th>Create At</th>
                                        <th style="width: 10%">ACTIONS</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            const csrfToken = "{{ csrf_token() }}";
            var option = {
                columns: [{
                        data: 'id',
                        render: function(data, type, full, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'title',
                        className: "title-post",
                    },
                    {
                        data: 'category_id'
                    },
                    {
                        data: 'user_id'
                    },
                    {
                        data: 'pay'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            const urlEdit = "{{ route('admin.posts.edit', ':id') }}".replace(':id', data);
                            const urlDel = "{{ route('admin.posts.destroy', ':id') }}".replace(':id', data);
                            return renderActions(urlDel, urlEdit, data, csrfToken)
                        }
                    }
                ],
                columnDefs: [{
                        targets: [1, 3, 4, 5, 7],
                        orderable: true
                    },
                    {
                        targets: [0, 1, 2, 7],
                        orderable: false
                    },
                    {
                        targets: 0,
                        orderable: false,
                        className: "no-sort"
                    }
                ],
                rowCallback: function(row, data) {
                    $(row).find('td:eq(0)').css('width', '20px');
                    $(row).find('td:eq(7)').css('width', '130px');
                    $(row).find('td:eq(8)').css('width', '140px');
                },
            }
            var dataTable = createDataTable('postDataTable', "{{ route('admin.posts.index') }}", option);
            // loadClick(dataTable);
        });
    </script>
@endpush
