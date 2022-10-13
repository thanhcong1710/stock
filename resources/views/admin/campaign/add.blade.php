@extends('admin.layouts.admin')
@section('head.title', 'Dashboard')
@section('body.content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!--begin::Row-->
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.campaign.save') }}">
                    @csrf

                    <div class="row g-9 mb-8">
                        <div class="d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Tiêu đề</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control " placeholder="" name="title" value="" required>
                        </div>
                        <div class="col-md-4 d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Mã cổ phiếu</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control " placeholder="" name="ma" value="" required>
                        </div>
                        <div class="col-md-4 d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Ngày bắt đầu</span>
                            </label>
                            <!--end::Label-->
                            <input type="date" class="form-control " placeholder="" name="start_date" value="" required>
                        </div>
                        <div class="col-md-4 d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Rate</span>
                            </label>
                            <!--end::Label-->
                            <input type="number" class="form-control " placeholder="" name="rate" value="" required>
                        </div>
                        <div class="text-center pt-15">
                            <a href="{{ route('admin.campaign.list') }}">
                                <button type="button" id="kt_modal_new_card_cancel" class="btn btn-light me-3">Hủy</button>
                            </a>
                            <button type="submit" id="kt_modal_new_card_submit" class="btn btn-success">
                                <span class="indicator-label">Save</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end::Content container-->
</div>
@stop