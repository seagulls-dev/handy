<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="{{$field}}">
        {{ !empty($title) ? $title : title_case($field)  }}
        @if(!empty($required)) <span class="required">*</span> @endif
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="select2" style="width:100%;" name="{{$field}}" @if($errors->has($field)) parsley-error @endif @if(!empty($required)) required @endif>
            <option value="">Select</option>
            @foreach($items as $item)
                <?php $k = is_object($item) ? $item->$key : $item ?>
                <option @if(!empty($selected) && $selected == $k) selected @endif value="{{ $k }}">{{ is_object($item) ? $item->$value : title_case($item)}}</option>
            @endforeach
        </select>
        @if($errors->has($field))
            <ul class="parsley-errors-list filled">
                @foreach($errors->get($field) as $error)
                    <li class="parsley-required">{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
