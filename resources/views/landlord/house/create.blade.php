@extends('layouts.backend.app')
@section('title')
   Add House
@endsection
@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card mt-5">
                    <div class="card-header">
                      <h3 class="card-title float-left"><strong>Add New House</strong></h3>
                  
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                    @include('partial.errors')

                    <form action="{{ route('landlord.house.store') }}" method="POST" enctype="multipart/form-data">
					        @csrf
					        <div class="form-group">
					          <label for="address">Address: </label>
					          <input type="text" class="form-control" placeholder="Enter address" id="address" name="address" value="{{ old('address') }}">
                            </div>

                            <div class="form-group">
                                <label for="area">Area </label>
                                <select name="area_id" class="form-control" id="area_id">
                                    <option value="">select an area</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}"  {{ old('area_id') == $area->id ? 'selected' : '' }} >{{ $area->name }}</option>
                                    @endforeach
                                </select>
                              </div>
                            
                            <div class="form-group">
                                <label for="number_of_room">Number of  rooms: </label>
                                <input type="text" class="form-control" placeholder="number_of_room" id="number_of_room" name="number_of_room" value="{{ old('number_of_room') }}">
                            </div>

                            <div class="form-group">
                                <label for="number_of_toilet">Number of toilet: </label>
                                <input type="text" class="form-control" placeholder="number_of_toilet" id="number_of_toilet" name="number_of_toilet" value="{{ old('number_of_toilet') }}">
                            </div>

                            <div class="form-group">
                                <label for="number_of_belcony">Number of  belcony: </label>
                                <input type="text" class="form-control" placeholder="number_of_belcony" id="number_of_belcony" name="number_of_belcony" value="{{ old('number_of_belcony') }}">
                            </div>

                            <div class="form-group">
                                <label for="description">Description: </label>
                                <textarea type="text" class="form-control" placeholder="Description" id="description" name="description" value="{{ old('description') }}"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="month">Available From Month: </label>
                                <select name="month" class="form-control" id="month">
                                    <option value="">Select Month</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="rent">Rent: </label>
                                <input type="text" class="form-control" placeholder="rent" id="rent" name="rent" value="{{ old('rent') }}">
                            </div>

                            <div class="form-group">
                                <label for="featured_image">Featured Image</label>
                                <input type="file" name="featured_image" class="form-control" id="featured_image">
                            </div>
        
                            <div class="form-group">
                                <label for="images">House Images</label>
                                <input type="file" name="images[]" class="form-control" multiple>
                            </div>

                            <div class="form-group">
                                <!-- <label for="map_link">Map Link: </label>
                                <input type="text" class="form-control" placeholder="map_link" id="map_link" name="map_link" value="{{ old('map_link') }}"> -->

                                <label for="map_link" >Map Link :</label>
                                <input class="form-control" placeholder="Enter map_link" name="map_link" id="map_link">
                                <!-- <input class="form-control" placeholder="Enter map_link" name="map_link" id="map_link"> -->
                            </div>
					      

                            <div class="form-group">
                                    <button type="submit" class="btn btn-success">Pay Now</button>
                                    <a href="{{ URL::previous() }}" class="btn btn-danger wave-effect" >Back</a>
                            </div> 
                  </form> 
                     
                      
                    </div>
                   
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
            </div>
        </div>
    </div><!-- /.container -->
 @endsection

 @section('scripts')
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script> -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<!-- for payment -->
<script>
    (function (window, document) {
        var loader = function () {
            var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
            script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
            tag.parentNode.insertBefore(script, tag);
        };

        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document);
</script>
<!-- for payment -->
@endsection