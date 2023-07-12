<div class="text-center" style="margin-bottom: 10px;">
	<img src="{{ $product->image === null ? asset('images/users_images/noimage.png') : asset('images/products/' . $product->image) }}" width="128px" alt="">
</div>


<table class="table table-striped">
	<tr>
		<th>Name</th>
		<th>:</th>
		<td>{{ $product->name }}</td>
	</tr>

	<tr>
		<th>Description</th>
		<th>:</th>
		<td>{{ $product->description }}</td>
	</tr>

	<tr>
		<th>Stock</th>
		<th>:</th>
		<td>{{ $product->stock }}</td>
	</tr>

	<tr>
		<th>Price</th>
		<th>:</th>
		<td>{{ $product->price }}</td>
	</tr>
</table>