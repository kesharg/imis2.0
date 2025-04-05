@foreach($formFields as $formField)
    <div class="form-group row">
        {!! Form::label($formField->labelFor,$formField->label,['class' => $formField->labelClass]) !!}
        <div class="col-sm-4">
            @if($formField->inputType === 'text')
                {!! Form::text($formField->inputId,$formField->inputValue,['class' => $formField->inputClass, 'placeholder' => $formField->placeholder]) !!}
            @endif
            @if($formField->inputType === 'number')
                {!! Form::number($formField->inputId,$formField->inputValue,['class' => $formField->inputClass, 'placeholder' => $formField->placeholder]) !!}
            @endif
            @if($formField->inputType === 'select')
                {!! Form::select($formField->inputId,$formField->selectValues,$formField->selectedValue,['class' => $formField->inputClass, 'placeholder' => $formField->placeholder]) !!}
            @endif
        </div>
    </div>
@endforeach
