@extends('layouts.global')

@section('title', '- Dashboard')

@section('page-header', 'Item Management Dashboard')

@section('content')
  <div class="row">
    @foreach($products as $key => $product)
      <div class="col-md-4">
        <div style="display: flex; align-items: center; flex-direction: column; width: 100%; justify-content: space-between; height: 480px !important;" class="card card-widget widget-user">
          <div class="widget-user-header {{ ($key + 1) / 2 === 0 ? 'bg-warning' : 'bg-info' }}" style="width: 100%;">
            <h3 class="widget-user-username">{{ $product->name }}</h3>
          </div>
          <div>
            <div style="width: 100%;">
              @if(!$product->image)
                <img style="width: 80px; height: 80px; margin-top: 40px; margin-bottom: 60px;" class="img-circle elevation-2" src="{{ asset('images/users_images/noimage.png') }}" alt="User Avatar">
              @else 
                <img style="height: 100px !important; width: 300px; !important; margin-top: 40px; margin-bottom: 60px;overflow: hidden !important; object-fit: stretch;" class="elevation-2" src="{{ asset('images/products/' . $product->image) }}" alt="User Avatar">
              @endif
            </div>
          </div>
          <div class="card-footer" style="width: 100%;">
            <div class="row">
              <div class="col-sm-6 border-right">
                <div class="description-block">
                  <h5 class="description-header">Price</h5>
                  <span class="description-text">{{ $product->price }}</span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="description-block">
                  <h5 class="description-header">Stock</h5>
                  <span class="description-text">{{ $product->stock }}</span>
                </div>
              </div>
            </div>
          </div>

          <button 
            class="btn btn-success btn-block btn-transaction-buy" 
            data-id="{{ $product->id }}"
          >
            Buy
          </button>
        </div>
      </div>
    @endforeach
  </div>
@endsection

@push('script')
	<script>
		$(function () {
      $('.btn-transaction-buy').on('click', function(event) {
        event.preventDefault()

        const csrf_token = $("meta[name=csrf-token]").attr("content")

        const formData = new FormData()
        formData.append('_token', csrf_token)
        formData.append('product_id', $(this).attr('data-id'))

        Swal.fire({
          title: "Are you sure want to buy?",
          text: "Choose Option Wisely",
          type: "info",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, I Do!",
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: '/dev/transactions',
              method: 'POST',
              contentType: false,
              processData: false,
              data: formData,
              beforeSend: function() {
                console.log("BEFORE SEND")
              },
              success: function(data) {
                toastr.success(data.message, "SUCCESS");
              },
              error: function() {
                console.log("ERROR")
              }
            })
          }
        })
      })

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