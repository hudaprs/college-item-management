{!! 
	Form::model($product, [
		'route' => isset($product) && $product->exists ? ['products.update', $product->id] : 'products.store',
		'method' => isset($product) && $product->exists ? 'PUT' : 'POST',
		'enctype' => 'multipart/form-data'
	])
!!}

	<div class="row items-center">
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('image', 'Image') }}
				<br>
				{{ Form::file('image') }}
			</div>
		</div>

		<div class="col-md-6">
			@if($product->exists)
				<img src="{{ !$product->image ? asset('images/users_images/noimage.png') : asset('images/products/' . $product->image) }}" width="64px">
			@endif
		</div>
	</div>

  <div class="row">
    <div class="col-md-12">
    	<div class="form-group">
    		{{ Form::label('name', 'Name') }}
    		{{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Product Name', 'autocomplete' => 'off']) }}
    	</div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
    	<div class="form-group">
    		{{ Form::label('description', 'Description') }}
    		{{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Product Description', 'autocomplete' => 'off']) }}
    	</div>
    </div>
  </div>

	<div class="row">
    <div class="col-md-12">
    	<div class="form-group">
    		{{ Form::label('stock', 'Stock') }}
    		{{ Form::number('stock', null, ['class' => 'form-control', 'placeholder' => 'Product Stock', 'autocomplete' => 'off']) }}
    	</div>
    </div>
  </div>

	<div class="row">
    <div class="col-md-12">
    	<div class="form-group">
    		{{ Form::label('price', 'Price') }}
    		{{ Form::number('price', null, ['class' => 'form-control', 'placeholder' => 'Product Price', 'autocomplete' => 'off']) }}
    	</div>
    </div>
  </div>

{!! Form::close() !!}
