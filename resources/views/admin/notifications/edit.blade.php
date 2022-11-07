@extends('layouts.dashboard')

@section('content')
    <div class="main py-4">



        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <form method="post"
                  action="{{isset($notification) && !request()->routeIs('admin.notifications.clone') ? route('admin.notifications.update', $notification->id) : route('admin.notifications.store')}}">
                @csrf
                @isset($notification)
                    @method('PATCH')
                @endisset

                <div class="d-flex justify-content-between">
                    <h2 class="mb-4 h5">{{ isset($notification) ?  __('Edit configuration') : __('Create configuration') }}</h2>
                    <div class="form-check form-switch ">

                        <input class="form-check-input" name="disabled" value="1"
                               @if(isset($notification) && $notification->disabled) checked @endif type="checkbox"
                               id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">{{__('Disabled')}}</label>
                    </div>
                </div>

                <x-input.text label="{{(__('Title'))}}"
                              name="title"
                              value="{{ isset($notification) ? $notification->title : null}}"/>

                <x-input.textarea label="{{(__('Email content'))}}"
                                  name="content"
                                  ckeditor
                                  value="{!!isset($notification) ? $notification->content : null!!}"/>

                <div class="form-group d-flex justify-content-end mt-3">
                    <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </div>
            </form>
        </div>
        <br />
        <div class="card card-body border-0 shadow table-wrapper table-responsive d-inline-flex">
            <div class="row">
                @foreach(json_decode($notification->models) as $a => $item)
                    <div class="col-sm-6">
                    <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">{{class_basename(new $item())}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">example: &lbrace;&lbrace;${{strtolower(class_basename(new $item()))}}->id&rbrace;&rbrace;</h6>
                                <p class="card-text">Hover over the following button below to view the available methods to use.</p>
                                <button type="button" class="btn btn-gray-700 fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="@foreach((new $item())->getFillable() as $b => $value)
                           {{preg_replace('/\s+/', ' ', $value)}} <br/>
                           @endforeach">
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection


