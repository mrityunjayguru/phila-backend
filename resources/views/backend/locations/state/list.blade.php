@extends('layouts.backend.master')

<style>
.modal-backdrop,
.blockOverlay {
    position: relative !important;
}
</style>
@section('filter')
<!-- FILTERS -->
<div class="modal fade" id="filterBox" tabindex="-1" role="dialog" aria-labelledby="filterBoxTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterTitle">{{trans('common.filters')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Page</span>
                            </div>
                            <input id="page" value="1" placeholder="Ex: 0 or 100" step="1" type="number"
                                class="form-control">
                        </div>
                    </div> -->
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Count</span>
                            </div>
                            <input id="count" value="20" placeholder="Ex: 0 or 100" step="1" type="number"
                                class="form-control">
                        </div>
                    </div>
                    <br />
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-group">
                                <!-- <label for="country" class="form-label">Country</label> -->
                                <select name="country_id" id="country_id" class="form-control">
                                    @if($countries->count())
                                    @foreach($countries as $items)
                                    <option value="{{$items->id}}">{{$items->country_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
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
                <button type="button" class="btn btn-secondary filter_apply_btn" data-dismiss="modal">Apply</button>
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
                    <span class="d-inline-block">{{ trans('state.heading') }}</span>
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="page-title-subheading opacity-10">
                <nav class="" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a><i aria-hidden="true" class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"> <a>{{ trans('state.plural') }}</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- CONTENT START -->
<div class="main-card mb-3 card">
    <div class="card-header-tab card-header">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">{{trans('state.title')}}</div>
        <div class="btn-actions-pane-right text-capitalize">
            <button type="button" class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm"
                data-toggle="modal" data-target="#filterBox">{{trans('common.filters')}}</button>
            <a class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm add_state_btn"
                href="javascript:void(0);">{{trans('state.add')}}</a>
        </div>
    </div>
    <div class="card-body">
        <h5 class="card-title"></h5>
        <div class="table-responsive">
            <table class="mb-0 table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('state.name') }}</th>
                        <th>{{ trans('common.status') }}</th>
                        <th>{{ trans('common.action') }}</th>
                    </tr>
                </thead>
                <tbody id="data-list"></tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="container">
        <div class="user_pagination d-flex justify-content-center">
            <ul class="nav" style="align-items: center">
                <li class="nav-item js_pagination_append" style="padding: 10px; ;"></li>
            </ul>
        </div>
    </div>
</div><br /><br />
<!-- CONTENT OVER -->

<div class="modal fade-out mt-5" id="state_edit_modal" tabindex="-1" role="dialog" aria-labelledby="filterBoxTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="state_edit_modal_title">Update State</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="country" class="form-label">Country</label>
                                <select name="edit_country_id" id="edit_country_id" class="form-control">
                                    @if($countries->count())
                                    @foreach($countries as $items)
                                    <option value="{{$items->id}}">{{$items->country_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="state_name" class="form-label">State Name</label>
                            <input type="text" name="state_name" id="state_name" class="form-control">
                            <div class="errors" id="state_name_err" style="font-size:14px;color:red;"></div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br>
                        <h5 class="card-title">Status</h5>
                        <div class=" form-group">
                            <div>
                                <div class="custom-radio custom-control">
                                    <input type="radio" value="active" id="status-active" name="editStatus" class="custom-control-input">
                                    <label class="custom-control-label" for="status-active">Active</label>
                                </div>
                                <div class="custom-radio custom-control">
                                    <input type="radio" value="inactive" id="status-inactive" name="editStatus" class="custom-control-input" checked>
                                    <label class="custom-control-label" for="status-inactive">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary add_update_state_btn" data-dismiss="modal">Update</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="page" name="page" value="0">
<input type="hidden" id="edit_id" name="edit_id" >
@endsection

@section('js')
<script>
$(document).ready(function(e) {
    var data = new FormData();
    data.append('page', $('#page').val());
    data.append('count', $('#filterBox #count').val());
    data.append('status', $('input[name="statusRadio"]:checked').val());
    data.append('country_id', $('#country_id').val());
    getData(data);

    $('.filter_apply_btn').click( () => {
        var data = new FormData();
        data.append('page', $('#page').val());
        data.append('count', $('#filterBox #count').val());
        data.append('status', $('input[name="statusRadio"]:checked').val());
        data.append('country_id', $('#country_id').val());
        getData(data);
    });

    $(document).on('change', '.status', function() {
        var data = new FormData();
        data.append('status', $(this).val());
        data.append('id', $(this).data('state_id'));
        var response = adminAjax('{{route("ajax.change.state.status")}}', data);
        if (response.status == '200') {
            if (response.data.status == 'success') {
                swal.fire({
                    type: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }
    });

    $('.add_state_btn').click( () => {
        $('#state_edit_modal_title').html('Add State');
        $('#state_name').val('');
        $('.add_update_state_btn').html('Submit');
        $('.add_update_state_btn').attr('data-checkoperation', 'add_section');
        // $('.add_update_state_btn').removeAttr('data-edit_id');
        $('#state_edit_modal').modal('show');
    });

    $(document).on('click','.add_update_state_btn', function () {
        let country_id = $('#edit_country_id').val();
        let state_name = $('#state_name').val();
        let status = $('input[name="editStatus"]:checked').val();
        let formValid = true;
        if(state_name == ''){
            $('#state_name_err').html('Enter state name');
            formValid =  false;
        }else{
            $('#state_name_err').html('');
        }

        if(formValid){
            let stateData = new FormData();
            stateData.append('checkoperation', $(this).data('checkoperation'));
            stateData.append('country_id', country_id);
            stateData.append('state_name', state_name);
            stateData.append('status', status);
            stateData.append('edit_id', $('#edit_id').val());
            let resp = adminAjax('{{route("ajax.add_states")}}', stateData);
            if(resp.status == 200){
                $('#state_edit_modal').modal('hide');
			    fireAlert('success', resp.message);
                getData(data);
            }
        }
       
    });

    $(document).on('click', '.edit_state', function() {
        let formData = new FormData();
        formData.append('state_id', $(this).data('state_id'));
        formData.append('status_filter', 'edit_section');
        let resp = adminAjax('{{route("state.edit.delete")}}', formData);
        if (resp.status == 200) {
            $('#state_edit_modal_title').html('Update State');
            $('.add_update_state_btn').html('Update');
            $('.add_update_state_btn').attr('data-checkoperation', 'update_section');
            // $('.add_update_state_btn').attr('data-edit_id',resp.data.id);
            $('#edit_id').val(resp.data.id);
            $('#state_name').val(resp.data.name);
            $('#state_edit_modal').modal('show');
        }

    });

    $(document).on('click','.page-link', function() {
        let checkPreNext = $(this).attr('aria-label');
        let page_no = $('#page').val();
        let page = parseInt($('#page').val());
        if(checkPreNext === 'pagination.next'){
            if($(this).attr('rel') == 'next'){
                $(this).html(page);
            }
            if(page_no == 0){
                page += 2;
            }else {
                page += 1;
            }
            
        }else if(checkPreNext === 'pagination.previous'){
            if($(this).attr('rel') == 'prev'){
                $(this).html(page);
            }
            page -= 1;
        }else {
            page = $(this).html();
        }
        PerPage = $('.userPerPage ').val();
        var pageData = new FormData();
        pageData.append('page', page);
        pageData.append('count', $('#filterBox #count').val());
        pageData.append('status', $('input[name="statusRadio"]:checked').val());
        pageData.append('country_id', $('#country_id').val());
        getData(pageData);
    });

});
// GET LIST
function getData($data) {
    var response = adminAjax('{{route("ajax.stateList")}}', $data);
    if (response.status == '200') {
        $('#data-list').empty();
        if (response.data.data.length > 0) {
            var htmlData = '';
            $.each(response.data.data, function(index, value) {
                //var date = moment.unix(value.timestamp).format("DD-MMMM-YYYY (h:mm a)");
                htmlData += '<tr>' +
                    '<th scope="row">' + parseInt(index + 1) + '</th>' +
                    '<td>' + value.name + '</td>';
                if (value.status == 'active') {
                    htmlData += '<td><select class="form-control status" data-state_id="' + value.id +
                        '"><option value="active" selected>Active</option><option value="inactive">Inactive</option></select></td>';
                } else {
                    htmlData += '<td><select class="form-control status" data-state_id="' + value.id +
                        '"><option value="active">Active</option><option value="inactive" selected>Inactive</option></select></td>';
                }
                htmlData += '<td><div class="widget-content-right widget-content-actions">' +
                    '<a class="border-0 btn-transition btn btn-outline-success edit_state" data-state_id="' +
                    value.id + '"><i class="fa fa-eye"></i></a>' +
                    '<button class="border-0 btn-transition btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button></div></td>' +
                    '</tr>';
            });
            $('#data-list').html(htmlData);
        }
        $('#page').val(response.data.current_page);
        $('.js_pagination_append').html(response.pagination);
        $('.page-link').attr('href','javascript:void(0);');
    }
}
</script>
@endsection