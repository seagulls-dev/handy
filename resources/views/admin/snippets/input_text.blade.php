<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="{{$field}}">
        {{ !empty($title) ? $title : str_replace("_", " " , title_case($field))  }}
        @if(!empty($required)) <span class="required">*</span> @endif
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="{{$field}}" type="{{ !empty($type) ? $type : 'text'  }}"
               class="form-control col-md-7 col-xs-12 @if($errors->has($field)) parsley-error @endif"
               name="{{$field}}" @if(!empty($required)) required @endif value="{{ old($field) ?: (isset($value) ? $value : '')  }}"
               @if(!empty($readonly)) readonly @endif>
        @if($errors->has($field))
        <ul class="parsley-errors-list filled">
            @foreach($errors->get($field) as $error)
            <li class="parsley-required">{{ $error }}</li>
            @endforeach
        </ul>
        @endif
    </div>
</div>