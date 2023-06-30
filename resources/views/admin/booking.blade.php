@extends('admin.adminlayout')
@section('bodycontent')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Booking </span> </h4>

        <!-- Basic Bootstrap Table -->
        <div class="card">
            <h5 class="card-header">Booking</h5>
            @if (session('msg'))
                <div class="alert y-2 alert-primary alert-dismissible" role="alert">
                    {{ session('msg') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="table-responsive text-nowrap">
                <table class="table table-striped table-hover">
                    <caption class="ms-4">
                        List of Booking
                    </caption>
                    <thead>
                        <tr>
                            <th>Name<br /> Email, Contact</th>
                            <th>Lawn (Locality)<br/> Lawn Contact </th>

                            <th>Booking for</th>

                            <th>Booking at</th>
                        </tr>
                    </thead>
                    @if ($bookings->count() > 0)
                        <tbody class="table-border-bottom-0">
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                        <strong>{{ $booking->name }}</strong><br />
                                        {{ $booking->email }}<br />{{ $booking->mobile }}
                                    </td>
                                    <td>
                                        {{ $booking->lawn_name }}<br/>({{ $booking->locality }})
                                        <br/>
                                        {{$booking->lawn_contact}}
                                       
                                    </td>
<td>{{ $booking->booking_date }}<br />{{ substr($booking->booking_notes, 0, 50) . '...' }}</td>

                                    <td>{{$booking->created_at}}</td>
                                    {{-- <td>
                                        @if ($booking->status)
                                            <span class="badge bg-label-primary me-1">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </td>
                                    <td>


                                        <form action="/booking_destroy/{{ $booking->id }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="dropdown-item" type="submit"><i class="bx bx-trash me-1"></i>
                                                Delete</a>
                                        </form>

                                    </td> --}}
                                </tr>
                            @endforeach

                        </tbody>
                    @endif
                </table>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->

        <hr class="my-5" />

    </div>
    <!-- / Content -->
@stop
