@extends('layouts.global')

@section('title', '- Trashed User Management')

@section('page-header', 'Trashed User Management')

@section('breadcumb-here', 'Dashboard')

@section('content')
	<div class="card card-solid card-danger">
		<div class="card-header">
			<div class="card-title">
				<div class="pull-left">
					List Trashed Users
				</div>
        <button style="margin-left:10px;" type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i>
        </button>
			</div>

			<div class="pull-right">
				<a href="{{ route('users.index') }}" class="btn btn-success">
					<span class="fa fa-check"></span> Untrashed Users
				</a>
				<a href="{{ route('users.trashed') }}" class="btn btn-secondary" id="btn-refresh"><span class="fa fa-refresh"></span></a>
			</div>
		</div>

		<div class="card-body">
			<div class="table table-responsive">
				<table class="table table-striped" id="datatable">
					<thead>
						<tr>
							<th>#</th>
							<th>Image</th>
							<th>NIP</th>
							<th>Name</th>
							<th>Division</th>
							<th>Email</th>
							<th>Level</th>
							<th></th>
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
				serverside: true,
				ajax: "{{ route('users.datatables.trashed') }}",
				columns: [
					{ data: 'DT_RowIndex', name: 'id' },
          { data: 'image', name: 'image' },
          { data: 'nip', name: 'nip' },
          { data: 'name', name: 'name' },
          { data: 'division', name: 'division' },
          { data: 'email', name: 'email' },
          { data: 'user_has_level.name', name: 'user_has_level.name' },
          { data: 'action', name: 'action' }
				]
			})
		})
	</script>
@endpush