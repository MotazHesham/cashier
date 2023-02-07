@extends('layouts.teacher')
@section('styles')
 <style media="screen">
     :root {
         --header-height: 3rem;
         --nav-width: 68px;
         --body-font: 'Nunito', sans-serif;
         --normal-font-size: 1rem;
         --z-fixed: 100;
         --body-color: #ECF0FA;
     	--payment-color: #0dcaf0;
     	--main-color: #2196f3;
     	--main-color-2: #2195f367;
     	--main-color-3: #2195f367;
     	--main-color-alt: #1787e0;
     	--white-color: #ffffff;

     }
     input[type="checkbox"] {
         display: none;
     }

     input[type="radio"] {
         display: none;
     }

     .filter{
         display: flex;
     }

     label {
         position: relative;
         color: black;
         font-size: 1.2rem;
         font-weight: 400;
         background-color: var(--body-color);
         border-radius: 5px;
         margin: 5%;
         padding: 5%;
         align-items: center;
     }



     input[type="checkbox"]:checked + label {
         background-color: var(--main-color);
         color: var(--white-color);
     }

     input[type="radio"]:checked + label {
         background-color: var(--main-color);
         color: var(--white-color);
     }

     input[name="payment_type"]:checked + label {
         background: #ECF0FA;
         color: var(--payment-color);
         box-shadow: 0px 6px 2px;
     }

     .partials-scrollable {
         max-height: 235px;
         height: 100%;
         overflow: scroll;
         overflow-x: hidden;
     }

     .partials-scrollable::-webkit-scrollbar {
         width: 5px;
     }

     .partials-scrollable::-webkit-scrollbar-track {
         background: rgba(0, 0, 0, .0);
         border-radius: 10px;
     }

     .partials-scrollable::-webkit-scrollbar-thumb {
         border-radius: 10px;
         background: var(--main-color-2);
     }

     .partials-scrollable::-webkit-scrollbar-thumb:hover {
         background: var(--main-color);
     }

 </style>
@endsection
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Edit Order <span class="badge badge-warning text-white">{{$order->code}}</span>
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="text-center">
                      <h4 class="mb-3">{{$order->description}}</h4>
                      <hr>
                    </div>

                  <form class="add-product" method="post">
                    @csrf
                    <div class="row mb-5">
                      <div class="col-md-3">
                        <div class="mb-2">
                          <span for="required">Students</span>
                          <select class="form-control select2" name="student_id" id="student_id">
                            @foreach($students as $student)
                              <option value="{{$student->id}}" >{{$student->user->name ?? ''}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="mb-2">
                          <span for="required">Categories</span>
                          <select class="form-control select2" name="category_id" id="category_id" onchange="get_products()">
                            <option value="" disabled selected> .. Please Select Category</option>
                            @foreach($categories as $category)
                              <option value="{{$category->id}}" >{{$category->name}}</option>
                            @endforeach
                          </select>
                        </div>


                        <div class="mb-2">
                          <span for="required">Products</span>
                          <select class="form-control select2" name="product_id" id="product_id" onchange="get_attributes()">
                            <option value="" disabled selected>... Select Category First</option>
                          </select>
                        </div>

                      </div>
                      <div class="col-md-6">
                        <span>الخيارات</span>
                        <div  id="product_attributes" style="border:1px grey dashed">
                        </div>
                        <!-- ajax call -->
                      </div>
                      <div class="col-md-3 mt-3">
                        <span>الكمية</span>
                        <input type="number" name="quantity" class="form-control mb-2"   id="quantity"  required>
                        <button type="submit" class="btn btn-success btn-block btn-lg">أضافة</button>
                      </div>
                    </div>
                  </form>

                  <form action="{{route('teacher.update_order')}}" method="Post" id="update_form">
                    @csrf
                    <input type="hidden" name="order_id" value="{{$order->id}}">
                    <div class="row">
                      @foreach($students as $student)
                        <div class="col-md-6">
                          <div class="card">
                            <div class="card-body">

                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="heading-student-{{$student->id}}">
                                            <h4 class="panel-title">
                                                <a class="collapsed text-center" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-student-{{$student->id}}" aria-expanded="true" aria-controls="collapse-student-{{$student->id}}">
                                                    {{ $student->user->name ?? ''}}
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse-student-{{$student->id}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-student-{{$student->id}}">
                                            <div class="panel-body">
                                                  <div id="div-table-receipt-{{$student->id}}">
                                                       <div class="partials-scrollable" style="max-height: 43vh">
                                                          <table id="table-receipt" class="table table-borderless table-striped" style="direction: rtl;">
                                                              <thead>
                                                                  <td>المنتج</td>
                                                                  <td>الكمية</td>
                                                                  <td>الأجمالي</td>
                                                                  <td></td>
                                                              </thead>
                                                              <tbody>
                                                                  @foreach($student->user->order_products()->where('order_id',$order->id)->get() as $order_product)
                                                                      @php
                                                                          $extra_price = 0;
                                                                          $product_price = $order_product->product->price ?? 0;
                                                                          Session::put('counter', Session::get('counter') + 1);
                                                                      @endphp
                                                                    <tr id="receipt-product-{{ Session::get('counter') }}">
                                                                        <td>
                                                                            {{ $order_product->product_name }}
                                                                            <br>

                                                                            @foreach (json_decode($order_product->attributes) as $row)
                                                                                @php
                                                                                    $extra_price += $row->price;
                                                                                @endphp
                                                                                <b class="badge bg-info">{{ $row->variant }}</b>
                                                                            @endforeach
                                                                            @php
                                                                                $product_cost_with_extra = $extra_price + $product_price;
                                                                            @endphp
                                                                        </td>
                                                                        <td>
                                                                            <input type="hidden" name="products[{{ Session::get('counter') }}][user_id]" value="{{$order_product->user_id}}">
                                                                            <input type="hidden" name="products[{{ Session::get('counter') }}][quantity]" value="{{$order_product->quantity}}">
                                                                            {{$order_product->quantity}}
                                                                        </td>
                                                                        <td id="receipt-product-cost-{{ Session::get('counter') }}" class="receipt-product-cost">
                                                                            {{ $product_cost_with_extra * $order_product->quantity }}
                                                                        </td>
                                                                        <td>
                                                                            <select name="products[{{ Session::get('counter') }}][attributes][]" id="" style="display: none" multiple>
                                                                                @foreach (json_decode($order_product->attributes) as $row)
                                                                                    <option value="{{ $row->variant }}" selected>{{ $row->variant }}</option>
                                                                                @endforeach
                                                                            </select> {{-- form purpose --}}

                                                                            <input type="hidden" name="products[{{ Session::get('counter') }}][product_id]" value="{{ $order_product->product_id }}">
                                                                            <input type="hidden" name="products[{{ Session::get('counter') }}][product_cost]" value="{{ $product_cost_with_extra }}">
                                                                            <button class="btn btn-outline-danger" type="button" onclick="removeTr({{ Session::get('counter') }})"><i class="fas fa-trash"></i></button>
                                                                        </td>
                                                                    </tr>

                                                                  @endforeach
                                                              </tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                    <button type="submit" class="btn btn-info btn-lg btn-block text-white">تعديل الطلب</button>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function() {
    get_products();
  })
  function get_products(){
    $.ajax({
        type: "POST",
        url: '{{ route('teacher.get_products') }}',
        data: {category_id:$('#category_id').val(),_token:'{{csrf_token()}}'},
        success: function(data) {
            $('#product_attributes').html(null);
            $('#product_id').html(null);
            $('#product_id').html(data);
            get_attributes();
        }
    });
  }

  function get_attributes(){
    $.ajax({
        type: "POST",
        url: '{{ route('teacher.get_attributes') }}',
        data: {product_id:$('#product_id').val(),_token:'{{csrf_token()}}'},
        success: function(data) {
            $('#product_attributes').html(null);
            $('#product_attributes').html(data);
        }
    });
  }

  $('.add-product').on('submit', function(event) {
      var student_id = $('#student_id').val();
      event.preventDefault();
      $.ajax({
          type: "POST",
          url: '{{ route('teacher.add_product') }}',
          data: $(this).serialize(),
          success: function(data) {
              $('#div-table-receipt-'+student_id+' tbody').append(data);
              //calculate_total_cost();
          }
      });
  })

  function removeTr(id) {
      $('#receipt-product-' + id).fadeOut(300, function() {
          $('#receipt-product-' + id).remove();
          //calculate_total_cost();
      });
  }
</script>
@endsection
