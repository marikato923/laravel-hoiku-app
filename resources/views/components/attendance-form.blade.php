<div class="tab-pane fade show active" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
    <h3 class="mt-4">登園打刻ページ</h3>

        <!-- 既存のフォーム（出席・退園フォーム） -->
        <div class="row">
            <form action="{{ route('attendance.register') }}" method="POST" class="col-12">
                @csrf
                {{-- <div class="form-group">
                    <label for="child_id">子供を選択</label>
                    <select class="form-control child-select" id="child_id" name="child_id" required>
                        <option value="" disabled selected>選択してください</option>
                        @foreach ($children as $child)
                            <option value="{{ $child->id }}" {{ old('child_id') == $child->id ? 'selected' : '' }}>
                                {{ $child->last_name }} {{ $child->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}
                
                <div class="form-group">
                    <label for="pickup_name">お迎えに来る人の名前</label>
                    <input type="text" class="form-control" id="pickup_name" name="pickup_name" value="{{ old('pickup_name') }}" required>
                </div>

                <div class="form-group">
                    <label for="pickup_time">お迎え予定時刻</label>
                    <input type="time" class="form-control" id="pickup_time" name="pickup_time" value="{{ old('pickup_time') }}" required>
                </div>

                <button type="submit" class="btn btn-success attendance-btn">登園</button>
            </form>

            <form action="{{ route('attendance.departure') }}" method="POST" class="col-12">
                @csrf
                {{-- <div class="form-group">
                    <label for="child_id_departure">子供を選択</label>
                    <select class="form-control child-select" id="child_id_absent" name="child_id" required>
                        <option value="" disabled selected>選択してください</option>
                        @foreach ($children as $child)
                            <option value="{{ $child->id }}" {{ old('child_id') == $child->id ? 'selected' : '' }}>
                                {{ $child->last_name }} {{ $child->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}
                <button type="submit" class="btn btn-danger departure-btn">降園</button>
            </form>
        </div>

    </script>