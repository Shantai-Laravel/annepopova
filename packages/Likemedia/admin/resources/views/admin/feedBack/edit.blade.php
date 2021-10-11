@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('feedback.index') }}">Feedback</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Feedback</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea FeedBack </h3>
</div>
@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('pages.update', $feedBack->id) }}" id="add-form" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <div class="tab-content  active-content">
            <div class="part full-part">

                <div class="col-md-6">
                    <h6>
                        <small>forma - </small> {{ $feedBack->form }}
                        @if ($feedBack->status === 'new')
                            <span class="label label-primary">new</span>
                        @elseif ($feedBack->status === 'procesed')
                            <span class="label label-success">procesed</span>
                        @elseif ($feedBack->status === 'cloose')
                            <span class="label label-danger">cloose</span>
                        @endif
                    </h6>
                    <ul>
                        <li>
                            <label for="first_name">Name</label>
                            <input type="text" name="first_name" class="name" id="first_name" value="{{ $feedBack->first_name }}">
                        </li>
                        {{-- <li>
                            <label for="second_name">Nume</label>
                            <input type="text" name="second_name" class="name" id="second_name" value="{{ $feedBack->second_name }}">
                        </li> --}}
                        <li>
                            <label for="email">Email</label>
                            <input type="text" name="email" class="name" id="email" value="{{ $feedBack->email }}">
                        </li>
                        <li>
                            <label for="company">Company</label>
                            <input type="text" name="company" class="name" id="company" value="{{ $feedBack->company }}">
                        </li>
                        <li>
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="name" id="phone" value="{{ $feedBack->phone }}">
                        </li>
                        @if ($feedBack->image)
                            <li>
                                <label for="phone">Attachment <a href="/images/leads/{{ $feedBack->image }}" class="btn btn-sm btn-primary pull-right" download><i class="fa fa-download"></i> Download </a></label>
                                <p class="text-center"><img src="/images/leads/{{ $feedBack->image }}" width="400px;"></p>
                            </li>
                        @endif
                        <li><br>
                            <label for="phone">Schimba statutul</label>
                            <a href="{{ url('back/feedback/clooseStatus/'.$feedBack->id.'/new') }}" class="btn btn-success btn-sm rounded-s">New</a>
                            <a href="{{ url('back/feedback/clooseStatus/'.$feedBack->id.'/procesed') }}" class="btn btn-success btn-sm rounded-s">In procesare</a>
                            <a href="{{ url('back/feedback/clooseStatus/'.$feedBack->id.'/cloose') }}" class="btn btn-success btn-sm rounded-s">Inchis</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" class="name" id="subject" value="{{ $feedBack->subject }}">
                        </li>
                        <li>
                            <label for="message">Message</label>
                            <textarea type="text" name="message" class="name" id="message" disabled>{{ $feedBack->message }}</textarea>
                        </li>

                        {{-- <li>
                            <label for="additional_1">Preorder</label>
                            <table class="table table-hover">
                                <tr>
                                    <td><b>#</b></td>
                                    <td><b>Name</b></td>
                                    <td><b>Code</b></td>
                                    <td><b>Price</b></td>
                                </tr>
                                {!! $feedBack->pre_order !!}
                            </table>

                            <table class="table table-hover">
                                <tr>
                                    <td><b>Prameter</b></td>
                                    <td><b>Value</b></td>
                                </tr>
                                {!! $feedBack->additional_3 !!}
                            </table>
                            <hr>
                            @if ($feedBack->additional_1)
                                <div class="text-right" style="padding-right: 50px;">
                                    Amount: <b>{{ $feedBack->additional_1 }} Lei</b>
                                </div>
                            @endif
                        </li> --}}

                    </ul>
                </div>

            </div>
        </div>
    </form>
</div>
@stop
@section('footer')
<style media="screen">
    td img{
        width: 330px;
    }
</style>
<footer>
    @include('admin::admin.footer')
</footer>
@stop
