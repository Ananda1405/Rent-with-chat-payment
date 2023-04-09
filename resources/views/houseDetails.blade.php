@extends('layouts.frontend.app')

@section('title','Home')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card my-5">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3><strong>House Details</strong></h3>

                        </div>
                        <div style="display: inherit;">
                            <a class="btn btn-danger" href="{{ route('welcome') }}"> Back</a>

                            @guest
                            <a style="margin-left:10px" href="" onclick="guestBooking()" class="btn btn-info">Apply for booking</a>
                            @else

                            @if (Auth::user()->role_id == 3)
                            <!-- <button class="btn btn-info" type="submit" onclick="renterBooking({{ $house->id }})">
                                Apply for booking
                            </button> -->


                            <form style="margin-left:10px" id="booking-form-{{ $house->id }}" action="{{ route('booking.payment', $house->id) }}"
                                method="POST" >
                                @csrf
                                <button class="btn btn-info" type="submit" >
                                    Apply for booking
                                </button>
                            </form>

                            @endif
                            @endguest
                        </div>
                    </div>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                @include('partial.successMessage')
                            </tr>
                            <tr>
                                    <th>House Code</th>
                                    <td>{{ $house->house_code }}</td>
                                </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $house->address }}</td>
                            </tr>
                            <tr>
                                <th>Area</th>
                                <td>{{ $house->area->name }}</td>
                            </tr>
                            <tr>
                                <th>Owner Name</th>
                                <td>{{ $house->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Owners Contact</th>
                                <td>{{ $house->contact }}</td>
                            </tr>
                            <tr>
                                <th>Number of rooms</th>
                                <td>{{ $house->number_of_room }}</td>
                            </tr>

                            <tr>
                                <th>Number of toilet</th>
                                <td>{{ $house->number_of_toilet }}</td>
                            </tr>

                            <tr>
                                <th>Number of belcony</th>
                                <td>{{ $house->number_of_belcony }}</td>
                            </tr>

                            <tr>
                                <th>Rent</th>
                                <td>{{ $house->rent }}</td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($house->status == 1)
                                    <span class="btn btn-success">Available</span>
                                    @else
                                    <span class="btn btn-danger">Not Available</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Share</th>
                                <td>
                                    <div class="addthis_inline_share_toolbox"></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="row gallery">
                        @foreach (json_decode($house->images) as $picture)
                        <div class="col-md-3">
                            <a href="{{ asset('images/'.$picture) }}">
                                <img src="{{ asset('images/'.$picture) }}" class="img-fluid m-2"
                                    style="height: 150px;width: 100%; ">
                            </a>
                        </div>
                        @endforeach
                    </div>

                    <!-- =========for Chat============== -->
                    <div class="row gallery">
                        <div class="col-md-12">
                            <span style="color:red">***To Know more details please <a href="/chatify">message</a> <strong>{{ $house->user->name }}</strong> & tell house code: <strong>{{ $house->house_code }}</strong>. </span>
                        </div>
                    </div>
                    <!-- =========for Chat============== -->

                     <!-- for map -->
                     <div class="row mt-4">
                        <h3 class="col-md-12 p-2 mb-3 text-success" style="background-color: #e1f1e9e5; font-size:29px;">Map</h3>
                        <div class="col-md-12 ml-2 pr-2">


                            @if (isset($house->map_link))
                                <iframe src="{{$house->map_link}}" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                            @else
                                <span style="font-size: 15px;"> Sorry!!! No Map Link Is Available To Show At This moment. </span>
                            @endif
                        </div>
                        </div> 


                        <!-- =============For Comment=============== -->

                    <div class="row mt-4">
                        <h3 class="col-md-12 p-2 mb-3 text-success" style="background-color: #e1f1e9e5; font-size:29px;">Comment</h3>
                        
                        </div> 

                        <!-- ====================copy start================== -->

                        <!-- Start comment-sec Area -->
            <section class="comment-sec-area pt-80 pb-80">
              <div class="container">
                <div class="row flex-column">
                  <h5 class="text-uppercase pb-80">{{$house->comments->count()}} Comments</h5>
                  <br />
                @foreach ($house->comments as $comment)
                <div class="comment">
                      <div class="comment-list">
                        <div class="single-comment justify-content-between d-flex">
                          <div class="user justify-content-between d-flex">
                            <div class="thumb">
                            <img src="{{asset('storage/profile_photo/'.$comment->user->image)}}" alt="{{$comment->user->image}}" width="50px">
                            </div>
                            <div class="desc">
                              <h5><a href="#">{{$comment->user->name}}</a></h5>
                              <p class="date">{{$comment->created_at->format('D, d M Y H:i')}}</p>
                              <p class="comment">
                                {{$comment->message}}
                              </p>
                            </div>
                          </div>
                          <div class="">
                            <button class="btn-reply btn btn-primary text-uppercase" id="reply-btn"
                              onclick="showReplyForm('{{$comment->id}}','{{$comment->user->name}}')">reply</button
                            >
                          </div>
                        </div>
                      </div>
                    @if($comment->replies->count() > 0)
                      @foreach ($comment->replies as $reply)
                      <div class="comment-list left-padding">
                        <div
                          class="single-comment justify-content-between d-flex"
                        >
                          <div class="user justify-content-between d-flex">
                            <div class="thumb">
                              <img src="{{asset('storage/user/'.$reply->user->image)}}" alt="{{$reply->user->image}}" width="50px"/>
                            </div>
                            <div class="desc">
                              <h5><a href="#">{{$reply->user->name}}</a></h5>
                              <p class="date">{{$reply->created_at->format('D, d M Y H:i')}}</p>
                              <p class="comment">
                                {{$reply->message}}
                              </p>
                            </div>
                          </div>
                          <div class="">
                            <button class="btn-reply btn btn-secondary text-uppercase" id="reply-btn"
                              onclick="showReplyForm('{{$comment->id}}','{{$reply->user->name}}')">reply</button
                            >
                          </div>
                        </div>
                      </div>

                      @endforeach
                    @else
                    @endif
                      {{-- When user login show reply fourm --}}
                      @guest
                      {{-- Show none --}}
                      @else
                      <div class="comment-list left-padding" id="reply-form-{{$comment->id}}" style="display: none">
                        <div
                          class="single-comment justify-content-between d-flex"
                        >
                          <div class="user justify-content-between d-flex">
                            <div class="thumb">
                              <img src="{{asset('storage/user/'.Auth::user()->image)}}" alt="{{Auth::user()->image}}" width="50px"/>
                            </div>
                            <div class="desc">
                              <h5><a href="#">{{Auth::user()->name}}</a></h5>
                              <p class="date">{{date('D, d M Y H:i')}}</p>
                              <div class="row flex-row d-flex">
                              <form action="{{route('reply.store',$comment->id)}}" method="POST">
                              @csrf
                                <div class="col-lg-12">
                                  <textarea
                                    id="reply-form-{{$comment->id}}-text"
                                    cols="60"
                                    rows="2"
                                    class="form-control mb-10"
                                    name="message"
                                    placeholder="Messege"
                                    onfocus="this.placeholder = ''"
                                    onblur="this.placeholder = 'Messege'"
                                    required=""
                                  ></textarea>
                                </div>
                                <button type="submit" class="btn-reply text-uppercase ml-3">Reply</button>
                              </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      @endguest
                  </div>
                 @endforeach
                </div>
              </div>
            </section>
            <!-- End comment-sec Area -->

            <!-- Start commentform Area -->
            <section class="commentform-area pb-120 pt-80 mb-100">
            @guest
                <div class="container">
                    <h4>Please Sign in to post comments - <a href="{{route('login')}}">Sing in</a> or <a href="{{route('register')}}">Register</a></h4>
                </div>
            @else
                <div class="container">
                  <h5 class="text-uppercas pb-50">Leave a Reply</h5>
                  <div class="row flex-row d-flex">
                      <div class="col-lg-12">
                          <form action="{{route('comment.store', $house->id)}}" method="POST">
                              @csrf
                          <textarea
                            class="form-control mb-10"
                            name="message"
                            placeholder="Messege"
                            onfocus="this.placeholder = ''"
                            onblur="this.placeholder = 'Messege'"
                            required=""
                          ></textarea>
                          <button style="margin-top: 10px;" type="submit" class="primary-btn btn btn-success mt-20" href="#">Comment</button>
                      </form>
                      </div>
                  </div>
                </div>
                @endguest
            </section>
            <!-- End commentform Area -->



                        
                        <!-- ====================copy end================== -->



                    </div>

                    

                    


                </div>



                <!-- /.card-body -->
            </div>
            <!-- /.card -->





        </div>
    </div>



    @if ($house->reviews->count() > 0)
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card my-5">
                <div class="card-header bg-dark text-white">
                    <strong>Renter Reviews of this house ({{ $house->reviews->count() }})</strong>
                </div>

                <div class="card-body">
                    @foreach ($house->reviews as $review)
                    <div class="card mb-3">
                        <div class="card-header">
                            <img class="mr-3"
                                src="{{ $review->user->image!=null ? asset('storage/profile_photo/'. $review->user->image) : asset('storage/profile_photo/default.png') }}"
                                width="35" height="35"
                                style="border-radius: 50%"><strong>{{ $review->user->name }}</strong>
                        </div>
                        <div class="card-body">
                            <p class="text-justify">
                                {{ $review->opinion }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif



</div><!-- /.container -->

@endsection




@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script>
    window.addEventListener('load', function() {
        baguetteBox.run('.gallery', {
            animation: 'fadeIn',
            noScrollbars: true
        });
   });

   function guestBooking(){
                Swal.fire(
                    'If you want to booking this house',
                    'Then you must have to login first as a renter',
                )
                event.preventDefault();     
    }

    function renterBooking(id){
           const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            
            swalWithBootstrapButtons.fire({
                title: 'Are you sure to booking this house?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        event.preventDefault();
                        document.getElementById('booking-form-'+id).submit();
                
                    } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                    ) {
                    swalWithBootstrapButtons.fire(
                        'Not Now!',
                        
                    )
                }
            })
       }

</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5f5fb96836345445"></script>

<!-- ==========for payment================ -->
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

<!-- =======For Comment============== -->

<script type="text/javascript">
     function showReplyForm(commentId,user) {
      var x = document.getElementById(`reply-form-${commentId}`);
      var input = document.getElementById(`reply-form-${commentId}-text`);

      if (x.style.display === "none") {
        x.style.display = "block";
        input.innerText=`@${user} `;

      } else {
        x.style.display = "none";
      }
    }
</script>


<!-- =======For Comment============== -->

@endsection



@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.css">
@endsection