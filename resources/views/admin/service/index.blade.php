@extends('layouts.admin') 

@section('styles')

<style type="text/css">
	tr td:first-child {
		background-color: #2d3274;
	}
.img-upload #image-preview {
  background-color: #2d3274 !important;
  background-size: unset !important;
  }
</style>

@endsection

@section('content')  
					<input type="hidden" id="headerdata" value="{{ __('SERVICE') }}">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">{{ __('Services') }}</h4>
										<ul class="links">
											<li>
												<a href="{{ route('admin.dashboard',$storename) }}">{{ __('Dashboard') }} </a>
											</li>
											<li>
												<a href="javascript:;">{{ __('Home Page Settings') }} </a>
											</li>
											<li>
												<a href="{{ route('admin-service-index',$storename) }}">{{ __('Services') }}</a>
											</li>
										</ul>
								</div>
							</div>
						</div>
						<div class="product-area">
							<div class="row">
								<div class="col-lg-12">
									<div class="mr-table allproduct">

                        @include('includes.admin.form-success')  

										<div class="table-responsiv">
												<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
									                        <th>{{ __('Featured Image') }}</th>
									                        <th width="30%">{{ __('Title') }}</th>
									                        <th width="40%">{{ __('Details') }}</th>
									                        <th>{{ __('Options') }}</th>
														</tr>
													</thead>
												</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>



{{-- ADD / EDIT MODAL --}}

										<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
										
										
										<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
												<div class="submit-loader">
														<img  src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
												</div>
											<div class="modal-header">
											<h5 class="modal-title"></h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											</div>
											<div class="modal-body">

											</div>
											<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
											</div>
										</div>
										</div>
</div>

{{-- ADD / EDIT MODAL ENDS --}}


{{-- DELETE MODAL --}}

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

	<div class="modal-header d-block text-center">
		<h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
	</div>

      <!-- Modal body -->
      <div class="modal-body">
            <p class="text-center">{{ __('You are about to delete this Service.') }}</p>
            <p class="text-center">{{ __('Do you want to proceed?') }}</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
            <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
      </div>

    </div>
  </div>
</div>

{{-- DELETE MODAL ENDS --}}

@endsection    



@section('scripts')


{{-- DATA TABLE --}}

    <script type="text/javascript">

		var table = $('#geniustable').DataTable({
			   ordering: false,
               processing: true,
               serverSide: true,
               ajax: '{{ route('admin-service-datatables',$storename) }}',
               columns: [
                        { data: 'photo', name: 'photo' , searchable: false, orderable: false},
                        { data: 'title', name: 'title' },
                        { data: 'details', name: 'details' },
            			{ data: 'action', searchable: false, orderable: false }

                     ],
                language : {
                	processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                }
            });

      	$(function() {
        $(".btn-area").append('<div class="col-sm-4 table-contents">'+
        	'<a class="add-btn" data-href="{{route('admin-service-create',$storename)}}" id="add-data" data-toggle="modal" data-target="#modal1">'+
          '<i class="fas fa-plus"></i> {{ __('Add New Service') }}'+
          '</a>'+
          '</div>');
      });											
									


{{-- DATA TABLE ENDS--}}


</script>





@endsection   