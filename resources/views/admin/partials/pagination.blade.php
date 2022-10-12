<?php 
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(strpos($url, "?") !== false){
    $url= $url."&";
} else{
    $url= $url."?";
}
?>
<div class="d-flex flex-stack flex-wrap pt-10">
	<div class="fs-6 fw-semibold text-gray-700">Showing {{($page-1)*$limit +1 }} to {{$page*$limit}} of {{$total}} entries</div>
	<!--begin::Pages-->
	<ul class="pagination">
		<li class="page-item previous">
			<a href="{{$url}}page={{$page>1 ? $page-1 : 1}}" class="page-link">
				<i class="previous"></i>
			</a>
		</li>
		@for($i=1;$i<7;$i++)
			@if($page<3)
			<li class="page-item {{$page==$i ? 'active' : ''}}">
				<a href="{{$url}}page={{$i}}" class="page-link">{{$i}}</a>
			</li>
			@else
			<li class="page-item {{$page==$i+$page-3 ? 'active' : ''}}">
				<a href="{{$url}}page={{$page+$i-3}}" class="page-link">{{$page+$i-3}}</a>
			</li>
			@endif
		@endfor
		<li class="page-item next">
			<a href="{{$url}}page={{$page+1}}" class="page-link">
				<i class="next"></i>
			</a>
		</li>
	</ul>
	<!--end::Pages-->
</div>