<div class="text-center" style="margin-bottom: 10px;">
	<img src="{{ $transaction->product_image === null ? asset('images/users_images/noimage.png') : asset('images/products/' . $transaction->product_image) }}" width="128px" alt="">
</div>


<table class="table table-striped">
	<tr>
		<th>Product Name</th>
		<th>:</th>
		<td>{{ $transaction->product_name }}</td>
	</tr>

	<tr>
		<th>Product Description</th>
		<th>:</th>
		<td>{{ $transaction->product_description }}</td>
	</tr>

	<tr>
		<th>Product Price</th>
		<th>:</th>
		<td>{{ $transaction->product_price }}</td>
	</tr>

	<tr>
		<th>Product Status</th>
		<th>:</th>
		<td>{{ $transaction->status }}</td>
	</tr>

	<tr>
		<th>Product Quantity</th>
		<th>:</th>
		<td>{{ $transaction->quantity }}</td>
	</tr>

	<tr>
		<th>Product Total Price</th>
		<th>:</th>
		<td>{{ $transaction->total_price }}</td>
	</tr>
</table>