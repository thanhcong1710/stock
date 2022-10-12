@extends('admin.layouts.admin')
@section('head.title', 'Dashboard')
@section('body.content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!--begin::Row-->
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.import.process') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex flex-column mb-7 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                            <span class="required">File import</span>
                        </label>
                        <!--end::Label-->
                        <input type="file" class="form-control " placeholder="" name="file" value="" required>
                    </div>
                    <div class="d-flex flex-column mb-7 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                            <span class="required">Mã cổ phiếu</span>
                        </label>
                        <!--end::Label-->
                        <input type="text" class="form-control " placeholder="" name="ma" value="" required>
                    </div>
                    <div class="text-center pt-15">
                        <button type="reset" id="kt_modal_new_card_cancel" class="btn btn-light me-3">Discard</button>
                        <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end::Content container-->
</div>
@stop