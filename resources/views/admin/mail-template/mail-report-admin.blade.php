RATING BY CUSTOMER<br/>
<br/>
Date rating : {{ date('Y/m/d H:i:s') }}<br/>
<br/>

@foreach ($data as $value)
Product Model : {!! $value['model'] !!}<br/>
Product Name : {!! $value['product_name'] !!}<br/>
Rate：{{ $value['rating'] }}<br/>
Rating By : {{ $value['author'] }}<br/>
Note : {{ $value['text'] }}<br/>
<hr>
@endforeach
<br/>
