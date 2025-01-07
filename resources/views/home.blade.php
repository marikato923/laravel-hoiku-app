@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>会員ホーム</h1>

        @if (session('flash_message'))
            <div class="container my-3">
                <div class="alert alert-info" role="alert">
                    <p class="mb-0">{{ session('flash_message') }}</p>
                </div>
            </div>
        @endif

        <div class="container">
            <div class="row">
                <!-- 子供を選択するプルダウン -->
                <form action="{{ route('attendance.register') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="child_id">子供を選択</label>
                        <select class="form-control" id="child_id" name="child_id" required>
                            <option value="" disabled selected>選択してください</option> <!-- 選択してくださいオプションを追加 -->
                            @foreach ($children as $child)
                                <option value="{{ $child->id }}" {{ old('child_id') == $child->id ? 'selected' : '' }}>
                                    {{ $child->last_name }} {{ $child->first_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- お迎えに来る人の名前 -->
                    <div class="form-group">
                        <label for="pickup_name">お迎えに来る人の名前</label>
                        <input type="text" class="form-control" id="pickup_name" name="pickup_name" value="{{ old('pickup_name') }}" required>
                    </div>

                    <!-- お迎え予定時刻 -->
                    <div class="form-group">
                        <label for="pickup_time">お迎え予定時刻</label>
                        <input type="time" class="form-control" id="pickup_time" name="pickup_time" value="{{ old('pickup_time') }}" required>
                    </div>

                    <button type="submit" class="btn btn-success">登園</button>
                </form>

                <!-- 退園フォーム -->
                <form action="{{ route('attendance.departure') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="child_id_departure">子供を選択</label>
                        <select class="form-control" id="child_id_absent" name="child_id" required>
                            <option value="" disabled selected>選択してください</option> <!-- 選択してくださいオプションを追加 -->
                            @foreach ($children as $child)
                                <option value="{{ $child->id }}" {{ old('child_id') == $child->id ? 'selected' : '' }}>
                                    {{ $child->last_name }} {{ $child->first_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger">降園</button>
                </form>
            </div>
        </div>
    </div>
@endsection