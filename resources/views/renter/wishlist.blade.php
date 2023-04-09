
@extends('layouts.frontend.app')
@section('title')
    Renter - Wishlist
@endsection
@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
               <!-- @include('partial.successMessage')   -->

                <div class="card my-5 mx-4">
                    <div class="card-header">
                      <h3 class="card-title float-left"><strong>Wishlist </strong></h3>
                      
                    </div>
                    <!-- /.card-header -->
                    <div class="">
                    <div class="table-responsive">
                      <table id="dataTableId" class="table table-bordered table-striped table-background">
                        <thead>
                        <tr>
                          <th>SL</th>
                          {{-- <th>Added at</th> --}}
                          <th>House Code</th>
                          <th>Address </th>
                          <th>Contact</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                         @foreach($wishlist as $key => $wishlist)
                         <tr>
                            <td>{{$key + 1 }}</td>

                            @php
                            $house = App\House::find($wishlist->house_id);
                            @endphp
                            <td>{{$house->house_code }}</td>
                            <td>{{$house->address }}</td>
                            <td>{{$house->contact }}</td>
                            <td>
                              <a href="{{ route('house.details', $house->id)  }}"  class="btn btn-success btn-sm">Details</a>

                              <form id="" action="{{ route('renter.cancel.wishlist', $wishlist->id) }}"
                                                method="POST" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger deleteBtn"
                                                    title="Delete Data"><i class="fa fa-trash"></i></button>
                                </form>
                              
                               {{-- <button class="btn btn-danger" type="button" onclick="cancel()">
                                Delete
                                </button>
            
                              <form id="cancel-form" action="{{ route('renter.cancel.wishlist', $wishlist->id) }}" method="POST" style="display: none;">
                                  @csrf
                              </form>--}}
                            </td>
                            </tr>
                        @endforeach
                        </tbody>
                      </table>
                    </div>
                      
            </div> <!-- /.card-body -->
              

               <div class="pagination">
                </div>
                   
            </div>
                  <!-- /.card -->
            </div>
        </div>
    </div><!-- /.container -->
 @endsection


 @section('scripts')
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
     <script>
         function cancel(){
           const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            
            swalWithBootstrapButtons.fire({
                title: 'Are you sure to remove this from Wishlist?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    
                    event.preventDefault();
                    document.getElementById('cancel-form').submit();
            
                } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
                ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                )
                }
            })
       }	
     </script>
 @endsection

