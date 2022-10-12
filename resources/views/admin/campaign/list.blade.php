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
                <form method="post" action="{{ route('admin.campaign.list') }}" >
                    <div class="d-flex flex-column mb-7 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                            <span class="required">Nhập từ khóa tìm kiếm</span>
                        </label>
                        <!--end::Label-->
                        <input type="text" class="form-control " placeholder="" name="keyword" value="">
                    </div>
                    <div class="text-center">
                        <a href="{{ route('admin.campaign.list') }}">
                            <button type="button" id="kt_modal_new_card_cancel" class="btn btn-light me-3">Hủy</button>
                        </a>
                        <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                            <span class="indicator-label">Tìm kiếm</span>
                        </button>
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
                                <th class="ps-4 min-w-300px rounded-start">Agent</th>
                                <th class="min-w-125px">Earnings</th>
                                <th class="min-w-125px">Comission</th>
                                <th class="min-w-200px">Company</th>
                                <th class="min-w-150px">Rating</th>
                                <th class="min-w-200px text-end rounded-end"></th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            <span class="symbol-label bg-light">
                                                <img src="/metronic8/demo1/assets/media/svg/avatars/001-boy.svg" class="h-75 align-self-end" alt="">
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Brad Simmons</a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">HTML, JS, ReactJS</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">$8,000,000</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Pending</span>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">$5,400</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Paid</span>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">Intertico</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Web, UI/UX Design</span>
                                </td>
                                <td>
                                    <div class="rating">
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                    </div>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7 mt-1">Best Rated</span>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4 me-2">View</a>
                                    <a href="#" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            <span class="symbol-label bg-light">
                                                <img src="/metronic8/demo1/assets/media/svg/avatars/047-girl-25.svg" class="h-75 align-self-end" alt="">
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Lebron Wayde</a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">PHP, Laravel, VueJS</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">$8,750,000</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Paid</span>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">$7,400</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Paid</span>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">Agoda</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Houses &amp; Hotels</span>
                                </td>
                                <td>
                                    <div class="rating">
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                    </div>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7 mt-1">Above Avarage</span>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4 me-2">View</a>
                                    <a href="#" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            <span class="symbol-label bg-light">
                                                <img src="/metronic8/demo1/assets/media/svg/avatars/006-girl-3.svg" class="h-75 align-self-end" alt="">
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Brad Simmons</a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">HTML, JS, ReactJS</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">$8,000,000</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">In Proccess</span>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">$2,500</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Rejected</span>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">RoadGee</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Paid</span>
                                </td>
                                <td>
                                    <div class="rating">
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                    </div>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7 mt-1">Best Rated</span>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4 me-2">View</a>
                                    <a href="#" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            <span class="symbol-label bg-light">
                                                <img src="/metronic8/demo1/assets/media/svg/avatars/014-girl-7.svg" class="h-75 align-self-end" alt="">
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Natali Trump</a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">HTML, JS, ReactJS</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">$700,000</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Pending</span>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">$7,760</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Paid</span>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">The Hill</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Insurance</span>
                                </td>
                                <td>
                                    <div class="rating">
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                    </div>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7 mt-1">Avarage</span>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4 me-2">View</a>
                                    <a href="#" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            <span class="symbol-label bg-light">
                                                <img src="/metronic8/demo1/assets/media/svg/avatars/020-girl-11.svg" class="h-75 align-self-end" alt="">
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Jessie Clarcson</a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">HTML, JS, ReactJS</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">$1,320,000</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Pending</span>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">$6,250</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Paid</span>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">Intertico</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Web, UI/UX Design</span>
                                </td>
                                <td>
                                    <div class="rating">
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                        <div class="rating-label me-2 checked">
                                            <i class="bi bi-star-fill fs-5"></i>
                                        </div>
                                    </div>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7 mt-1">Best Rated</span>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4 me-2">View</a>
                                    <a href="#" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">Edit</a>
                                </td>
                            </tr>
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