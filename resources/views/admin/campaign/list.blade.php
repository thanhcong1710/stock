@extends('admin.layouts.admin')
@section('head.title', 'Dashboard')
@section('body.content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!--begin::Row-->
        <div class="card mb-5 mb-xl-8">
            <!--begin::Header-->
            <div class="card-body">
                <form method="get" action="{{ route('admin.campaign.list') }}" >
                    <div class="col-md-6 d-flex flex-column mb-7 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                            <span>Nhập từ khóa tìm kiếm</span>
                        </label>
                        <!--end::Label-->
                        <input type="text" class="form-control " placeholder="" name="keyword" value="{{$keyword}}">
                    </div>
                    <div class="text-center">
                        <a href="{{ route('admin.campaign.list') }}">
                            <button type="button" id="kt_modal_new_card_cancel" class="btn btn-light me-3">Hủy</button>
                        </a>
                        <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                            <span class="indicator-label">Tìm kiếm</span>
                        </button>
                        <a href="{{ route('admin.campaign.add') }}">
                            <button type="button" class="btn btn-success">Thêm mới</button>
                        </a>
                    </div>
                </form>
            </div>
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Danh sách Campaign</span>
                </h3>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-3">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle gs-0 gy-4">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-100px rounded-start">STT</th>
                                <th class="min-w-300px">Tiêu đề</th>
                                <th class="min-w-150px">Mã cổ phiếu</th>
                                <th class="min-w-150px">Rating</th>
                                <th class="min-w-150px"></th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                        @foreach($list AS $k=>$row)
                            <tr>
                                <td>
                                    {{ ($page-1)*$limit + $k+1}}
                                </td>
                                <td>
                                    {{$row->title}}
                                </td>
                                <td>
                                    {{$row->ma}}
                                </td>
                                <td>
                                    {{$row->rate}} %
                                </td>
                                <td>
                                    <a href="{{route('admin.campaign.process',['campaign_id'=>$row->id])}}" title="Click để xử lý" target="blank">
                                        <span class="svg-icon svg-icon-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z" fill="currentColor" />
                                                <path opacity="0.3" d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                @include('admin.partials.pagination')
                <!--end::Table container-->
            </div>
            <!--begin::Body-->
        </div>
    </div>
    <!--end::Content container-->
</div>
@stop