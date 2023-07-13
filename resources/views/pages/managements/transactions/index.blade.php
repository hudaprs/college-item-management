@extends('layouts.global')

@section('title', '- Transactions Management')

@section('page-header', 'Transactions Management')

@section('breadcumb-here', 'Dashboard')

@section('content')
	<div class="card card-solid card-primary">
		<div class="card-header">
			<div class="card-title">
				<div class="pull-left">
					List Transactions
				</div>
        <button style="margin-left:10px;" type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i>
        </button>
			</div>

			<div class="pull-right">
				<a href="{{ route('transactions.index') }}" class="btn btn-secondary" id="btn-refresh"><span class="fa fa-refresh"></span></a>
			</div>
		</div>

		<div class="card-body">
			<div class="table table-responsive">
				<table class="table table-striped" id="datatable">
					<thead>
						<tr>
							<th>#</th>
							<th>Product Name</th>
							<th>Status</th>
							<th>Created At</th>
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
				ajax: "{{ route('transactions.index') }}",
				columns: [
					{ data: 'DT_RowIndex', name: 'id' },
					{ data: 'product_name', name: 'product_name' },
					{ data: 'status', name: 'status' },
					{ data: 'created_at', name: 'created_at' },
					{ data: 'action', name: 'action' }
				]
			})
		})
	</script>
@endpush