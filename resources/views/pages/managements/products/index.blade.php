@extends('layouts.global')

@section('title', '- Products Management')

@section('page-header', 'Products Management')

@section('breadcumb-here', 'Dashboard')

@section('content')
	<div class="card card-solid card-primary">
		<div class="card-header">
			<div class="card-title">
				<div class="pull-left">
					List Products
				</div>
        <button style="margin-left:10px;" type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i>
        </button>
			</div>

			<div class="pull-right">
				<a href="{{ route('products.create') }}" class="btn btn-success" id="btn-modal-show" title="Create New Product">
					<span class="fa fa-plus"></span> Create New Product
				</a>
				<a href="{{ route('products.index') }}" class="btn btn-secondary" id="btn-refresh"><span class="fa fa-refresh"></span></a>
			</div>
		</div>

		<div class="card-body">
			<div class="table table-responsive">
				<table class="table table-striped" id="datatable">
					<thead>
						<tr>
							<th>#</th>
							<th>Image</th>
							<th>Name</th>
							<th>Stock</th>
							<th>Price</th>
							<th style="text-align: center;">Action</th>
						</tr>
					</thead>

					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@push('script')
	<script>
		$(function () {
			$("#datatable").DataTable({
				processing: true,
				responsive: true,
				serverSide: true,
				ajax: "{{ route('products.index') }}",
				columns: [
					{ data: 'DT_RowIndex', name: 'id' },
					{ data: 'image', name: 'image'  },
					{ data: 'name', name: 'name' },
					{ data: 'stock', name: 'stock' },
					{ data: 'price', name: 'price' },
					{ data: 'action', name: 'action' }
				],
				'columnDefs': [ 
					{
						'targets': [1], /* column index */
						'orderable': false, /* true or false */
					}
				]
			})
		})
	</script>
@endpush