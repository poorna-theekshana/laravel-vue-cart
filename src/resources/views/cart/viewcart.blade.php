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
                <h5 class="card-header">Cart Items</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        @if (!empty($cartData))
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Product ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Image</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalQuantity = 0;
                                    @endphp

                                    @foreach ($cartData as $cartItem)
                                        @php
                                            $totalQuantity += $cartItem['quantity'];
                                        @endphp
                                        @if ($cartItem['product'])
                                            <tr>
                                                <td>{{ $cartItem['product']->id }}</td>
                                                <td>{{ $cartItem['product']->pdct_name }}</td>
                                                <td>{{ $cartItem['product']->pdct_description }}</td>
                                                <td>{{ $cartItem['product']->pdct_price }}</td>
                                                <td>{{ $cartItem['quantity'] }}</td>
                                                <td class="col-md-4 row"><img
                                                        src="{{ asset($cartItem['product']->getImageURL()) }}"
                                                        class="card-img-top resized-table-imag "
                                                        alt="{{ $cartItem['product']->pdct_name }}"></td>
                                                <td>
                                                    <form
                                                        action="{{ route('cart.delete', ['product_id' => $cartItem['product']->id]) }}"
                                                        method="post"
                                                        onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <hr>
                            <h2 class="text-right">Total Amount: {{ $totalAmount }}</h2>
                            <hr>

                            <form class="btn btn-primary btn-block"
                                action="{{ route('stripe', ['cartItemId' => $cartData, 'quantity' => $totalQuantity, 'totalAmount' => $totalAmount]) }}"
                                method="post">
                                @csrf
                                @method('post')
                                <button type="submit" class="btn btn-primary">Checkout</button>
                            </form>
                        @else
                            <p>Your cart is empty.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
