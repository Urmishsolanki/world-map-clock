@extends('admin.layouts.default')
@section('content')
<main id="main" class="main">
    <section class="section add-city-section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <h5 class="card-title">Add City</h5>
                  <a href="{{ route('home') }}" ><span class="btn btn-primary">Back to List</span></a>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {!! implode('', $errors->all('<div><li>:message</li></div>')) !!}
                    </div>
                @endif
                <!-- Multi Columns Form -->
                <form class="row g-3" action="{{route('city.store')}}" method="post" id="searchFrom" name="worldMapClockAddForm">
                    @csrf
                    <div class="col-md-12 placeSearch">
                        @if(old('name'))
                            @php $name = old('name'); @endphp
                        @else
                            @php $name = ""; @endphp
                        @endif
                        <label for="inputName5" class="form-label">Name</label>
                        <input type="text" class="form-control" id="city_name" name="name" value="{{$name}}" placeholder="Enter City">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="btnsubmit">Add</button>
                    </div>
                </form><!-- End Multi Columns Form -->
            </div>
        </div>
    </section>

</main><!-- End #main -->
@endsection