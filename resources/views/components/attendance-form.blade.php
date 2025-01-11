<div class="tab-pane fade show active" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
    <h3 class="mt-4">登園打刻ページ</h3>
    
 <!-- デジタル時計表示 -->
        <div id="clock" style="font-size: 3rem; text-align: center; margin-bottom: 20px; color: #FF6347; font-family: 'Comic Sans MS', cursive, sans-serif;"></div>

        @if (session('flash_message'))
            <div class="container my-3">
                <div class="alert alert-info" role="alert">
                    <p class="mb-0">{{ session('flash_message') }}</p>
                </div>
            </div>
        @endif

        <!-- 既存のフォーム（出席・退園フォーム） -->
        <div class="row">
            <form action="{{ route('attendance.register') }}" method="POST" class="col-12">
                @csrf
                <div class="form-group">
                    <label for="child_id">子供を選択</label>
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

                <button type="submit" class="btn btn-success attendance-btn">登園</button>
            </form>

            <form action="{{ route('attendance.departure') }}" method="POST" class="col-12">
                @csrf
                <div class="form-group">
                    <label for="child_id_departure">子供を選択</label>
                    <select class="form-control child-select" id="child_id_absent" name="child_id" required>
                        <option value="" disabled selected>選択してください</option>
                        @foreach ($children as $child)
                            <option value="{{ $child->id }}" {{ old('child_id') == $child->id ? 'selected' : '' }}>
                                {{ $child->last_name }} {{ $child->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-danger departure-btn">降園</button>
            </form>
        </div>

        <!-- ボタンを横並びにするためのスタイルを追加 -->
        <div class="row">
            <div class="col-6">
                <button type="submit" class="btn btn-success w-100 attendance-btn">登園</button>
            </div>
            <div class="col-6">
                <button type="submit" class="btn btn-danger w-100 departure-btn">降園</button>
            </div>
        </div>
    </div>

    <!-- 時計を表示するためのJavaScript -->
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateClock, 1000);
        updateClock(); 
    </script>