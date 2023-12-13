@extends('layouts.app')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Product Table</h5>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('product.create') }}" class="btn btn-success">+ Add Product</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Date Created</th>
                                    <th>Date Modified</th>
                                    <th>Image</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->pdct_name }}</td>
                                        <td>{{ $product->pdct_description }}</td>
                                        <td>{{ $product->pdct_price }}</td>
                                        <td>{{ $product->pdct_qty }}</td>
                                        <td>{{ $product->created_at }}</td>
                                        <td>{{ $product->updated_at }}</td>
                                        <td><img src="{{ asset($product->getImageURL()) }}" class="card-img-top resized-table-image"
                                                alt="{{ $product->pdct_name }}"></td>
                                        <td>
                                            <form action="{{ route('product.edit', ['product' => $product]) }}"
                                                method="get">
                                                @csrf
                                                <button type="submit" class="btn btn-info">Edit</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="{{ route('product.delete', ['product' => $product->id]) }}"
                                                method="post"
                                                onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
