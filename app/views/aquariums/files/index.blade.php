@extends('layout')
@section('content')

<h2>Photos</h2>

@foreach ($errors->all() as $message)
  <h4>{{ $message }}</h4>
@endforeach

<br />
<table>
  <tr><th colspan="2">Photos</th></tr>

@if (count($files) > 0)

  @foreach ($files as $file)
    <tr>
      <td class="image">
        <a href="/files/{{ $file->userID }}/{{ $file->fileID }}-full.{{ $file->fileType }}">
          <img src="/files/{{ $file->userID }}/{{ $file->fileID }}-thumb.{{ $file->fileType }}" />
        </a>
      </td>
      <td>
        Title: {{ $file->title }}<br />
        Caption: {{ $file->caption }}<br />
        Uploaded On: {{ $file->createdAt }}<br />
      </td>
    </tr>
  @endforeach

@else
  <tr>
    <td colspan="2">No Photos Found</td>
  </tr>
@endif
</table>

@stop
