@extends('layouts.admin')
@section('title', 'プロフィールの編集')

@section('content')
    <div class ="container">
        <div class ="row">
            <div class="col-md-8 mx-auto">
                <h2>プロフィールの編集</h2>
                <form action ="{{ action('Admin\ProfileController@update') }}" method ="post" enctype="multipart/form-date">
                    @if(count($errors) >0)
                        <ul>
                            @foreach($errors->all() as $e)
                                <li>{{  $e }}</li>
                            @endforeach
                        </ul>
                    @endif
                
                    <div class="form-group row">
                        <label class ="col-md-2" for="name">名前</label>
                        <div class ="col-md-10">
                         <input type="text" class="form-control" name="name" value="{{ $profile_form->name }}">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label class ="col-md-2" for="gender">性別</label>
                        <div class ="col-md-10">
                            @if($profile_form->gender =='male')
                                <input type="radio" class="col-md-2" name="gender" value="male" checked>男
                                <input type="radio" class="col-md-2" name="gender" value="female">女
                            @else
                                <input type="radio" class="col-md-2" name="gender" value="male" >男
                                <input type="radio" class="col-md-2" name="gender" value="female" checked>女  
                            @endif
                        </div>
                    </div>
                    <div class ="form-group row">
                         <label class="col-md-2" for="hobby">趣味</label>
                        <div class ="col-md-10">
                            <input type="text" class="form-control" name="hobby" value ="{{ $profile_form->hobby }}">
                        </div>
                    </div>
                    <div class ="form-group row">
                        <label class="col-md-2" for="introduction">自己紹介</label>
                        <div class ="col-md-10">
                            <textarea class="form-control" name="introduction" row="10" value="{{ $profile_form->introduction}}"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-10">
                            <input type="hidden" name="id" value ="{{ $profile_form->id }}">
                            {{ csrf_field() }}
                            <input type ="submit" class="btn btn-primary" value="更新">
                         </div>
                    </div>
                </form>
                
                <div class="row mt-5">
                    <div class="col-md-4 mx-auto">
                        <h2>プロフィールの編集履歴</h2>
                        <ul class ="list-group">
                            @if($profile_form->profilehistories != NULL)
                                @foreach($profile_form->profilehistories as $profilehistory)
                                    <li class="list-group-item">{{ $profilehistory->edited_at }}</li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection