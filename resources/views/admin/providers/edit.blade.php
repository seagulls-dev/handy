@extends('admin.layouts.admin')

@section('title', 'Edit "' . $provider->user->name . '"' )

@section('content')
    <div class="row" id="app2">
        <div class="col-md-12 col-sm-12 col-xs-12">
            {{ Form::open(['route'=>['admin.providers.update', $provider->id],'method' => 'put','class'=>'form-horizontal form-label-left', 'enctype' => 'multipart/form-data']) }}

            @include('admin.snippets.input_checkbox', ['field' => 'is_verified', 'checked' => $provider->is_verified])

            @include('admin.snippets.input_text', ['field' => 'document', 'type' => 'file'])

            @include('admin.snippets.textarea', ['field' => 'about', 'required' => true, 'value' => $provider->about])

            @include('admin.snippets.input_text', ['field' => 'price', 'required' => true, 'value' => $provider->price])

            @include('admin.snippets.input_select', [
                'field' => 'primary_category_id', 'title' => 'Price Type', 'items' => [(object)['id' => 'visit', 'value' => 'Per Visit'], (object)['id' => 'hour', 'value' => 'Per Hour']], 'key' => 'id',
                'value' => 'value', 'selected' => $provider->price_type, 'required' => true])

            @include('admin.snippets.textarea', ['field' => 'address', 'required' => true, 'value' => $provider->address])

            @include('admin.snippets.input_text', ['field' => 'latitude', 'required' => true, 'value' => $provider->latitude])

            @include('admin.snippets.input_text', ['field' => 'longitude', 'required' => true, 'value' => $provider->longitude])

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="primary_category_id">
                    Select Category
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="select_box" style="width:100%;" name="primary_category_id" @if($errors->has('primary_category_id')) parsley-error @endif @if(!empty($required)) required @endif v-on:change="onPrimaryCategoryChange()" v-model="primary_category_id">
                        <option value="">Select</option>
                        @foreach($categories as $item)
                            <option @if(!empty($provider->primary_category_id) && $provider->primary_category_id == $item->id) selected @endif value="{{ $item->id }}">{{ $item->title  }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('primary_category_id'))
                        <ul class="parsley-errors-list filled">
                            @foreach($errors->get('primary_category_id') as $error)
                                <li class="parsley-required">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="primary_category_id">
                    Select Sub Category
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="select_box" style="width:100%;" name="primary_category_id" @if($errors->has('primary_category_id')) parsley-error @endif @if(!empty($required)) required @endif multiple v-model="selected_subcategories">
                        <option v-for="subcategory in subcategories" :value="subcategory.id">@{{subcategory.title}}</option>
                    </select>
                    @if($errors->has('primary_category_id'))
                        <ul class="parsley-errors-list filled">
                            @foreach($errors->get('primary_category_id') as $error)
                                <li class="parsley-required">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

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
    {{ Html::style(mix('assets/admin/css/form.css')) }}
    {{ Html::style(mix('assets/admin/css/users/select2.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/select2.js')) }}
    <script>
        const app = new Vue({
            el: '#app2',
            data: {
                'primary_category_id': "{{$provider->primary_category_id}}",
                'subcategories': @json($subcategories),
                'selected_subcategories': @json($selected_subcategories)
            },
            methods: {
                onPrimaryCategoryChange() {
                    var self = this;
                    axios.get("{{route('admin.json.subcategories')}}?category_id=" + this.primary_category_id, {
                        data:'_token = <?php echo csrf_token() ?>'
                    })
                    .then(function (response) {
                        self.subcategories = response.data ? response.data : []
                    })
                    .catch(function (error) {
                    });
                }
            },
            mounted: function () {
                $('.select2').select2();
            }
        });

    </script>
@endsection