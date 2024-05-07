@extends('admin.layouts.default')
@section('content')
<main id="main" class="main">
    <section class="section edit-page">      
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <h5 class="card-title">Edit City</h5>   
                  <a href="{{ route('home') }}" ><span class="btn btn-primary">Back to List</span></a>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {!! implode('', $errors->all('<div><li>:message</li></div>')) !!}
                    </div>
                @endif
                <!-- Multi Columns Form -->
                <form class="row g-3" method="POST" action="{{route('city.update',$city->id)}}" id="searchFrom" name="searchFrom" >
                    @csrf       
                    @method('PUT')         
                    <div class="col-md-12">
                        @php                            
                            if((old('name')) != ""){
                                $name = old('name');
                            }elseif($city->name  != ""){
                                $name = $city->name ;
                            }else{
                                $name = "";
                            }
                        @endphp 
                        <label for="inputName5" class="form-label">Name</label>
                        <input type="text" class="form-control" id="city_name" name="name" value="{{$name}}" placeholder="Enter City">                        
                    </div>  
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="btnsubmit">Update</button>
                    </div>
                </form><!-- End Multi Columns Form -->
            </div>
        </div>
    </section>

</main><!-- End #main -->
@endsection