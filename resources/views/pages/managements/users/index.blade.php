@extends('layouts.global')

@section('title', '- Users Management')

@section('page-header', 'Users Management')

@section('breadcumb-here', 'Dashboard')

@section('content')
	<div class="card card-solid card-primary">
		<div class="card-header">
			<div class="card-title">
				<div class="pull-left">
					List Users
				</div>
        <button style="margin-left:10px;" type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i>
        </button>
			</div>

			<div class="pull-right">
				<a href="{{ route('users.create') }}" class="btn btn-success" id="btn-modal-show" title="Create New User">
					<span class="fa fa-plus"></span> Create New User
				</a>
				<a href="{{ route('users.index') }}" class="btn btn-secondary" id="btn-refresh"><span class="fa fa-refresh"></span></a>
			</div>
		</div>

		<div class="card-body">
			<div class="table table-responsive">
				<table class="table table-striped" id="datatable">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
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
				ajax: "{{ route('users.index') }}",
				columns: [
					{ data: 'DT_RowIndex', name: 'id' },
					{ data: 'name', name: 'name' },
					{ data: 'email', name: 'email' },
					{ data: 'role', name: 'role' },
					{ data: 'action', name: 'action' }
				]
			})
		})
	</script>
@endpush