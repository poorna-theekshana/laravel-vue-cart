@extends('layouts.app')

@section('content')
    @if (session()->has('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <h5 class="card-header">Purchase Details</h5>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th >Purchase ID</th>
                                <th>Date Created</th>
                                <th>Total Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($userpurchases as $purchase)
                                <tr class="table-active">
                                    <td>{{ $purchase->purchase_id }}</td>
                                    <td>{{ $purchase->date_created }}</td>
                                    <td>{{ $purchase->total_amount }}</td>
                                    <td>
                                        <a class="expand-button btn dropdown-toggle" data-toggle="collapse"
                                            href="#details-{{ $purchase->purchase_id }}" >
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                                <tr class="collapse" id="details-{{ $purchase->purchase_id }}">
                                    <td colspan="4">
                                        <table class="table table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Product ID</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($purchaseDetails as $purchaseDetail)
                                                    @if ($purchaseDetail->purchase_id === $purchase->purchase_id)
                                                        <tr>
                                                            <td>{{ $purchaseDetail->product_id }}</td>
                                                            <td>{{ $purchaseDetail->product_name }}</td>
                                                            <td>{{ $purchaseDetail->description }}</td>
                                                            <td>{{ $purchaseDetail->quantity }}</td>
                                                            <td>{{ $purchaseDetail->price }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No purchase details found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
