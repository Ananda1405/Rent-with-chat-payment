@extends('layouts.backend.app')
@section('title')
    All Comments
@endsection
@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
               @include('partial.successMessage')  

                <div class="card my-5 mx-4">
                    <div class="card-header">
                      <h3 class="card-title float-left"><strong>All Comments</strong></h3>
                      
                    </div>
                    <!-- /.card-header -->
                    <div class="">
                    <div class="table-responsive">
                      <table id="dataTableId" class="table table-bordered table-striped table-background">
                        <thead>
                        <tr>
                          <th>Comment</th>
                          <th>User</th>
                          <th>House </th>
                          <th>Created At </th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($comments as $key => $comment)
                                        <tr>
                                            
                                            <td>{{$comment->message}}</td>
                                            <td>{{$comment->user->name}}</td>
                                            <td>{{$comment->house->house_code}}</a></td>
                                            <td>{{$comment->created_at->diffForHumans()}}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger mb-1" data-toggle="modal"
                                                    data-target="#deleteModal-{{$comment->id}}">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach


                          
                        </tbody>
                      </table>
                    </div>
                      
            </div> <!-- /.card-body -->
             

               <div class="pagination">
                  {{ $comments->links() }}
                </div>
                   
            </div>
                  <!-- /.card -->
            </div>
        </div>
    </div><!-- /.container -->
 @endsection

 @section('scripts')
 <script src="{{asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backend/vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('backend/vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('backend/vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('backend/vendors/jszip/dist/jszip.min.js')}}"></script>
<script src="{{asset('backend/vendors/pdfmake/build/pdfmake.min.js')}}"></script>
<script src="{{asset('backend/vendors/pdfmake/build/vfs_fonts.js')}}"></script>
<script src="{{asset('backend/vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('backend/vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('backend/vendors/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('backend/assets/js/init-scripts/data-table/datatables-init.js')}}">
    <script>
        $(document).ready(function () {

            (function ($) {

                $('#filter').keyup(function () {

                    var rex = new RegExp($(this).val(), 'i');
                    $('.searchable tr').hide();
                    $('.searchable tr').filter(function () {
                        return rex.test($(this).text());
                    }).show();

                })

            }(jQuery));

        });
    </script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
        <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
@endsection