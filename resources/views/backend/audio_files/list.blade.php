@extends('layouts.backend.master')

@section('filter')
    <!-- FILTERS -->
    <div class="modal fade" id="filterBox" tabindex="-1" role="dialog" aria-labelledby="filterBoxTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterTitle">{{ trans('common.filters') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input id="search" placeholder="Type to search" step="1" type="text"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Page</span>
                                </div>
                                <input id="page" value="1" placeholder="Ex: 0 or 100" step="1"
                                    type="number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Count</span>
                                </div>
                                <input id="count" value="10" placeholder="Ex: 0 or 100" step="1"
                                    type="number" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <h5 class="card-title">Type</h5>
                            <div class="position-relative form-group">
                                <div>
                                    <div class="custom-radio custom-control">
                                        <input type="radio" id="tour" value="tour" name="typeRadio"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="tour">Tour</label>
                                    </div>
                                    <div class="custom-radio custom-control">
                                        <input type="radio" id="fairmount_park_loop" value="fairmount_park_loop"
                                            name="typeRadio" class="custom-control-input">
                                        <label class="custom-control-label" for="fairmount_park_loop">Fairmount Park
                                            Loop</label>
                                    </div>
                                    <div class="custom-radio custom-control">
                                        <input type="radio" id="mix" value="mix" name="typeRadio"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="mix">Mix</label>
                                    </div>
                                    <div class="custom-radio custom-control">
                                        <input type="radio" id="all" value="all" name="typeRadio"
                                            class="custom-control-input" checked>
                                        <label class="custom-control-label" for="all">All</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <h5 class="card-title">Status</h5>
                            <div class="position-relative form-group">
                                <div>
                                    <div class="custom-radio custom-control">
                                        <input type="radio" id="active" value="active" name="statusRadio"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="active">Active</label>
                                    </div>
                                    <div class="custom-radio custom-control">
                                        <input type="radio" id="inactive" value="inactive" name="statusRadio"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="inactive">Inactive</label>
                                    </div>
                                    <div class="custom-radio custom-control">
                                        <input type="radio" id="all" value="all" name="statusRadio"
                                            class="custom-control-input" checked>
                                        <label class="custom-control-label" for="all">All</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="getData();"
                        data-dismiss="modal">Apply</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="app-page-title app-page-title-simple">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div>
                    <div class="page-title-head center-elem">
                        <span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
                        <span class="d-inline-block">Audio</span>
                    </div>
                </div>
            </div>

            <div class="page-title-actions">
                <div class="page-title-subheading opacity-10">
                    <nav class="" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true"
                                        class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"> <a>Audio</a></li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>




    <!-- CONTENT START -->
    <div class="main-card mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">Audio List</div>
            <div class="btn-actions-pane-right text-capitalize">
                {{-- <a class="btn btn-sm btn-danger mt-2" href="{{ route('map.trigger-points',$page_id) }}" target="_blank">View Trigger Points</a> --}}
                <!--<button type="button" class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm"-->
                <!--    data-toggle="modal" data-target="#filterBox">{{ trans('common.filters') }}</button>-->
                <div class="position-relative form-group mt-2 ml-3 d-inline-block">
                    <label for="gps-validation" class="mr-2">GPS Validation</label>
                    <div class="custom-control custom-switch d-inline-block">
                        <input type="checkbox" class="custom-control-input" id="gps-validation" name="gps-validation">
                        <label class="custom-control-label" for="gps-validation">Yes/No</label>
                    </div>
                </div>
                @if (Settings::UserPermission('stop-create'))
                    <a class="btn-wide mt-2 btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm"
                        href="{{ route('audio-create',$page_id) }}">
                        {{ trans('common.add_new') }} </a>
                @endif

            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title"></h5>
            <div class="table-responsive">
                <input type="hidden" name="page_id" id="page_id" value="{{$page_id}}">
                <table class="mb-0 table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('common.image') }}</th>
                            <th>{{ trans('common.title') }}</th>
                            <!-- <th>Priority</th> -->
                            <th>{{ trans('common.status') }}</th>
                            <th>{{ trans('common.created_at') }}</th>
                            <th>{{ trans('common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="data-list"></tbody>
                </table>
            </div>
        </div>
        <div class="btn-actions-pane-right mb-3 text-capitalize">
            <button type="button"
                class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm previous-btn">{{ trans('common.previous') }}</button>
            <button type="button"
                class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm next-btn">{{ trans('common.next') }}</button>
        </div>
    </div>
    <!-- CONTENT OVER -->
@endsection
@section('js')
    <script>
        $(document).ready(function(e) {
            var page = $('#filterBox #page').val();
            getData(page);

            $('.next-btn').click(function() {
                page = Number($('#filterBox #page').val()) + 1;
                getData(page);
            });

            $('.previous-btn').click(function() {
                let currentPage = Number($('#filterBox #page').val());
                let newPage = currentPage - 1;
                if (newPage < 1) {
                    newPage = 1; // Ensure page is not less than 1
                }
                $('#filterBox #page').val(newPage);
                getData(newPage);
            });
        });

        // GET LIST
        function getData(page = 1) {
            var data = new FormData();
            data.append('page', page);
            data.append('page_id', $('#page_id').val());
            data.append('count', $('#filterBox #count').val());
            data.append('search', $('#filterBox #search').val());
            data.append('status', $('input[name="statusRadio"]:checked').val());
            data.append('type', $('input[name="typeRadio"]:checked').val());
            var response = adminAjax('{{ route('audio-list') }}', data);
            if (response.status == '200') {
                $('#data-list').empty();
                if (response.data.length > 0) {
                    $('#filterBox #page').val(page);
                    var uni_index = $('#filterBox #count').val() * (page - 1);
                    var htmlData = '';
                    $.each(response.data, function(index, value) {
                        console.log(value.image);
                        uni_index = uni_index + 1;
                        htmlData += '<tr>' +
                            '<th scope="row">' + uni_index + '</th>' +
                            '<td><img src="' + value.image + '" height="50px" width="50px"></td>' +
                            '<td>' + value.title + '</td>' +
                            // '<td>' + value.priority + '</td>' +
                            '<td>' + value.status + '</td>' +
                            '<td>' + value.created_at + '</td>' +
                            '<td>' + value.action + '</td>' +
                            '</tr>';
                    })
                    $('#data-list').html(htmlData);
                }
            }
        }

        // DELETE
        function deleteThis(item_id = '') {
            if (confirm("{{ trans('common.delete_confirm') }}")) {
                var data = new FormData();
                data.append('item_id', item_id);
                var response = adminAjax('{{ route('delete-audio') }}', data);
                if (response.status == '200') {
                    swal.fire({
                        type: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 2000)

                } else {
                    swal.fire({
                        title: response.message,
                        type: 'error'
                    });
                }
            }
        }
    </script>


    <script>
        $(document).ready(function() {
            function loadGPSSetting() {
                $.ajax({
                    url: '{{ route('gps.get') }}?t=' + new Date().getTime(),
                    type: 'GET',
                    success: function(response) {
                        $('#gps-validation').prop('checked', response.gps_enabled == 1);
                    },
                    error: function(xhr) {
                        console.error('Error loading GPS validation setting:', xhr.responseText);
                    }
                });
            }

            loadGPSSetting();

            $('#gps-validation').change(function() {
                var gpsEnabled = $(this).is(':checked') ? 1 : 0;
                var previousState = !gpsEnabled;


                var confirmMessage = gpsEnabled ? 'Are you sure you want to enable GPS validation?' :
                    'Are you sure you want to disable GPS validation?';
                if (confirm(confirmMessage)) {
                    $(this).prop('disabled', true);
                    $.ajax({
                        url: '{{ route('gps.update') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            gps_enabled: gpsEnabled
                        },
                        success: function(response) {
                            console.log('GPS validation setting updated successfully:',
                                response);
                            $('#gps-validation').prop('disabled',
                                false);
                        },
                        error: function(xhr) {
                            console.error('Error updating GPS validation setting:', xhr
                                .responseText);
                            $('#gps-validation').prop('checked',
                                previousState);
                            $('#gps-validation').prop('disabled',
                                false);
                        }
                    });
                } else {
                    $(this).prop('checked', previousState); // Revert checkbox state if user cancels
                }
            });
        });
    </script>
@endsection
