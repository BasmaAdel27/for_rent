@if($status == 'is_active')
<h3 style="text-align: right"> مرحبا  <bold> {{$name}} </bold></h3>
<br>
<h2 style="text-align: center">يؤسفنا اخبارك انه قد تم حظر حسابك من موقعنا</h2>
<h3><bold>شكرا لك </bold></h3>
    @else
<h3 style="text-align: right"> مرحبا  <bold> {{$name}} </bold></h3>
<br>
<h2 style="text-align: center">يسعدنا اخبارك بانه تم فك الحظر عن حسابك </h2>
<h3><bold>شكرا لك </bold></h3>
@endif
