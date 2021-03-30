@extends('admin.layouts.admin')

@section('title', 'Edit "' . $category->title . '"' )

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            {{ Form::open(['route'=>['admin.categories.update', $category->id],'method' => 'put','class'=>'form-horizontal form-label-left', 'enctype' => 'multipart/form-data']) }}

            @include('admin.snippets.input_text', ['field' => 'title', 'required' => true, 'value' => $category->title])

            @include('admin.snippets.input_text', ['field' => 'image_url', 'type' => 'file'])

            @include('admin.snippets.input_select', [
                'field' => 'parent_id', 'title' => 'Select Parent', 'items' => $categories, 'key' => 'id',
                'value' => 'title', 'selected' => $category->parent_id])

            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a class="btn btn-primary" href="{{ URL::previous() }}"> {{ __('views.admin.users.edit.cancel') }}</a>
                    <button type="submit" class="btn btn-success"> {{ __('views.admin.users.edit.save') }}</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/users/edit.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/edit.js')) }}
@endsection