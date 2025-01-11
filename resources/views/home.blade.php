@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>会員ホーム</h1>

        <x-tab-navigation />
        
        <div class="row">
            <form action="{{ route('attendance.register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="child_id">お子様を選択</label>
                    <select class="form-control child-select" id="child_id" name="child_id" required>
                        <option value="" disabled selected>選択してください</option>
                        @foreach ($children as $child)
                            <option value="{{ $child->id }}" {{ old('child_id') == $child->id ? 'selected' : '' }}>
                                {{ $child->last_name }} {{ $child->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="pickup_name">お迎えに来る人の名前</label>
                    <input type="text" class="form-control" id="pickup_name" name="pickup_name" value="{{ old('pickup_name') }}" required>
                </div>

                <div class="form-group">
                    <label for="pickup_time">お迎え予定時刻</label>
                    <input type="time" class="form-control" id="pickup_time" name="pickup_time" value="{{ old('pickup_time') }}" required>
                </div>

                <button type="submit" class="btn attendance-btn">登園</button>
            </form>

            <form action="{{ route('attendance.departure') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="child_id_departure">子供を選択</label>
                    <!-- 背景色を変更して、選択しやすくする -->
                    <select class="form-control child-select" id="child_id_absent" name="child_id" required>
                        <option value="" disabled selected>選択してください</option>
                        @foreach ($children as $child)
                            <option value="{{ $child->id }}" {{ old('child_id') == $child->id ? 'selected' : '' }}>
                                {{ $child->last_name }} {{ $child->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn departure-btn">降園</button>
            </form>
        </div>
    </div>
@endsection