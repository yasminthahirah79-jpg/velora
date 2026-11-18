<div class="f">@foreach($cfg['fields'] as $k => [$l, $t])
<div><label>{{ $l }}</label>
@if($t == 'select')<select name="{{ $k }}">@foreach($cfg['opts'][$k] as $v => $lab)<option value="{{ $v }}" @selected(old($k, $item?->$k) == $v)>{{ $lab }}</option>@endforeach</select>
@elseif($t == 'password')<input type="password" name="{{ $k }}" placeholder="{{ $item ? 'Kosongkan jika tidak diubah' : '' }}">
@else<input type="{{ $t }}" name="{{ $k }}" value="{{ old($k, $item?->$k) }}" @if($t == 'number') step="any" @endif>@endif</div>
@endforeach</div>
