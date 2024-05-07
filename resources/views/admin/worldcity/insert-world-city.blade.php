@extends('admin.layouts.default')
@section('content')
<main id="main" class="main">
    <section class="section password-page">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <h5 class="card-title">Add World City</h5>
                  <a href="{{ url('/world-city') }}" ><span class="btn btn-primary">Back to List</span></a>
                </div>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Multi Columns Form -->
                <form class="row g-3" action="{{route('world-city.store')}}" method="post" id="searchFrom" name="searchFrom">
                    @csrf                    
                    <div class="col-md-12">
                        <label for="inputName5" class="form-label">Country<sup class="text-danger">*</sup></label>
                        <select class="form-select" name="country" id="country">
                            <option value="">Select Country</option>
                                @foreach($countryData as $countryValue)
                                    @if(old('country') == $countryValue->name)
                                        @php $set = "selected='selected'"; @endphp
                                    @else
                                         @php $set = ""; @endphp
                                    @endif
                                    <option value="{{$countryValue->iso2}}" {{$set}}>{{ $countryValue->name }} ({{ $countryValue->iso2 }})</option>
                                @endforeach
                        </select>
                        <div class="text-danger">{{ $errors->first('country') }}</div>
                        <span class="error" id="fuel_type_span"></span>
                    </div>
                    <div class="col-md-6">
                        @if(old('name'))
                            @php $name = old('name'); @endphp
                        @else
                            @php $name = ""; @endphp
                        @endif
                        <label for="inputName5" class="form-label">City<sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{$name}}">
                        <div class="text-danger">{{ $errors->first('name') }}</div>
                    </div>
                    <div class="col-md-6">
                        @if(old('timezone'))
                            @php $timezone = old('timezone'); @endphp
                        @else
                            @php $timezone = ""; @endphp
                        @endif
                        <label for="inputName5" class="form-label">Timezone<sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="timezone" name="timezone" placeholder="Enter Timezone" value="{{$timezone}}">
                        <div class="text-danger">{{ $errors->first('timezone') }}</div>
                    </div>
                    <div class="col-md-6">
                        @if(old('latitude'))
                            @php $latitude = old('latitude'); @endphp
                        @else
                            @php $latitude = ""; @endphp
                        @endif
                        <label for="inputName5" class="form-label">Latitude<sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Enter Latitude" value="{{$latitude}}">
                        <div class="text-danger">{{ $errors->first('latitude') }}</div>
                    </div>
                    <div class="col-md-6">
                        @if(old('longitude'))
                            @php $longitude = old('longitude'); @endphp
                        @else
                            @php $longitude = ""; @endphp
                        @endif
                        <label for="inputName5" class="form-label">Longitude<sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Enter Longitude" value="{{$longitude}}">
                        <div class="text-danger">{{ $errors->first('longitude') }}</div>
                    </div>                    
                    <div class="text-">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form><!-- End Multi Columns Form -->
            </div>
        </div>
    </section>
</main><!-- End #main -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function () {
    setTimeout(function(){
      jQuery('.alert-success').fadeOut(1000);
  }, 1500);
});
</script>
@endsection