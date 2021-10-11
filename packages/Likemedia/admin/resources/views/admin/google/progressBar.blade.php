@extends('admin::admin.google.app')
@section('content')

<div class="wrapp">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-6">
            <p class="text-center status">{{ $data }} uploading...</p>
            <div class="progress green">
                <span class="progress-left">
                    <span class="progress-bar"></span>
                </span>
                <span class="progress-right">
                    <span class="progress-bar"></span>
                </span>
                <div class="progress-value"><span class="count">100</span>%</div>
            </div>
            <hr>
            <p class="text-center back-link"><a href="{{ url()->previous() }}"><small>Go back</small></a></p>
        </div>
    </div>
</div>
</div>

<style media="screen">
.row{
    padding-top: 200px;
}
.progress{
width: 150px;
height: 150px;
line-height: 150px;
background: none;
margin: 0 auto;
box-shadow: none;
position: relative;
}
.progress:after{
content: "";
width: 100%;
height: 100%;
border-radius: 50%;
border: 12px solid #fff;
position: absolute;
top: 0;
left: 0;
}
.progress > span{
width: 50%;
height: 100%;
overflow: hidden;
position: absolute;
top: 0;
z-index: 1;
}
.progress .progress-left{
left: 0;
}
.progress .progress-bar{
width: 100%;
height: 100%;
background: none;
border-width: 12px;
border-style: solid;
position: absolute;
top: 0;
}
.progress .progress-left .progress-bar{
left: 100%;
border-top-right-radius: 80px;
border-bottom-right-radius: 80px;
border-left: 0;
-webkit-transform-origin: center left;
transform-origin: center left;
}
.progress .progress-right{
right: 0;
}
.progress .progress-right .progress-bar{
left: -100%;
border-top-left-radius: 80px;
border-bottom-left-radius: 80px;
border-right: 0;
-webkit-transform-origin: center right;
transform-origin: center right;
animation: loading-1 1.8s linear forwards;
}
.progress .progress-value{
width: 90%;
height: 90%;
border-radius: 50%;
background: #44484b;
font-size: 24px;
color: #fff;
line-height: 135px;
text-align: center;
position: absolute;
top: 5%;
left: 5%;
}
.progress.blue .progress-bar{
border-color: #049dff;
}
.progress.blue .progress-left .progress-bar{
animation: loading-2 1.5s linear forwards 1.8s;
}
.progress.yellow .progress-bar{
border-color: #fdba04;
}
.progress.yellow .progress-left .progress-bar{
animation: loading-3 1s linear forwards 1.8s;
}
.progress.pink .progress-bar{
border-color: #ed687c;
}
.progress.pink .progress-left .progress-bar{
animation: loading-4 0.4s linear forwards 1.8s;
}
.progress.green .progress-bar{
border-color: #1abc9c;
}
.progress.green .progress-left .progress-bar{
animation: loading-5 7.5s linear forwards 2.0s;
}
@keyframes loading-1{
0%{
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
}
100%{
    -webkit-transform: rotate(180deg);
    transform: rotate(180deg);
}
}
@keyframes loading-2{
0%{
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
}
100%{
    -webkit-transform: rotate(180deg);
    transform: rotate(180deg);
}
}
@keyframes loading-3{
0%{
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
}
100%{
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg);
}
}
@keyframes loading-4{
0%{
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
}
100%{
    -webkit-transform: rotate(36deg);
    transform: rotate(36deg);
}
}
@keyframes loading-5{
0%{
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
}
100%{
    -webkit-transform: rotate(180deg);
    transform: rotate(180deg);
}
}
@media only screen and (max-width: 990px){
.progress{ margin-bottom: 20px; }
}
.back-link{
    display: none;
}
</style>

<script>
$('.count').each(function () {
    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    }, {
        duration: 10000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});

setTimeout(function(){
    $('.status').html('{{ $data }} uploaded successfully!');
    $('.back-link').show();
}, 10000);

</script>

@stop
