@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>会員ホーム</h1>

        <form id="attendanceForm">
            @csrf
            <h2>登園・降園</h2>

            {{-- 子供のリストとチェックボックス --}}
            <div class="form-group">
                <label>お子様を選択</label>
                @foreach ($children as $child)
                    <div class="form-check">
                        <input class="form-check-input child-checkbox" type="checkbox" id="child_{{ $child->id }}" name="children[]" value="{{ $child->id }}">
                        <label class="form-check-label" for="child_{{ $child->id }}">
                            {{ $child->last_name }} {{ $child->first_name }}
                        </label>
                    </div>
                @endforeach
            </div>

            {{-- 登園用フォーム --}}
            <div class="form-group">
                <label for="pickup_name">お迎えに来る予定の方</label>
                <input type="text" class="form-control" id="pickup_name" name="pickup_name" required disabled>
            </div>

            <div class="form-group">
                <label for="pickup_time">お迎え予定時刻</label>
                <input type="time" class="form-control" id="pickup_time" name="pickup_time" required disabled>
            </div>

            {{-- ボタン --}}
            <button type="button" class="btn btn-primary attendance-btn" id="arrivalBtn" disabled>登園</button>
            <button type="button" class="btn btn-secondary departure-btn" id="departureBtn" disabled>降園</button>
        </form>

        <div id="resultMessage" class="mt-3"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxes = document.querySelectorAll('.child-checkbox');
            const arrivalBtn = document.getElementById('arrivalBtn');
            const departureBtn = document.getElementById('departureBtn');
            const pickupName = document.getElementById('pickup_name');
            const pickupTime = document.getElementById('pickup_time');
            const resultMessage = document.getElementById('resultMessage');

        // 今日の日付をキーにしたストレージを管理
        function getTodayKey() {
            return `attendance_status_${new Date().toISOString().split('T')[0]}`; // yyyy-mm-dd
        }

        // 登園・降園のステータスをロード
        function loadAttendanceStatus() {
            return JSON.parse(localStorage.getItem(getTodayKey())) || {};
        }

        // 登園・降園のステータスを保存
        function saveAttendanceStatus(status) {
            localStorage.setItem(getTodayKey(), JSON.stringify(status));
        }

        // ボタン状態を更新する
        function updateButtonStates() {
            const selectedChildIds = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            const anySelected = selectedChildIds.length > 0;
            const storedStatus = loadAttendanceStatus();
            const allSelectedArrived = selectedChildIds.every(childId => storedStatus[childId] === true);

            // 登園ボタンの制御
            arrivalBtn.disabled = !anySelected || storedStatus['arrival_done'] === true;
            pickupName.disabled = !anySelected || storedStatus['arrival_done'] === true;
            pickupTime.disabled = !anySelected || storedStatus['arrival_done'] === true;

            // 降園ボタンの制御
            departureBtn.disabled = !anySelected || !allSelectedArrived || storedStatus['departure_done'] === true;

            // ボタンのクラスを更新
            updateButtonClasses(arrivalBtn, !arrivalBtn.disabled, 'btn-success');
            updateButtonClasses(departureBtn, !departureBtn.disabled, 'btn-danger');
        }

        // ボタンのクラスを更新
        function updateButtonClasses(button, isEnabled, activeClass) {
            button.classList.toggle('btn-secondary', !isEnabled);
            button.classList.toggle(activeClass, isEnabled);
        }

        // 登園ボタンが押されたとき
        arrivalBtn.addEventListener('click', async () => {
            const selectedChildIds = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            try {
                const response = await fetch('/api/attendance/arrival', {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ children: selectedChildIds }),
                });

                const data = await response.json();

                if (response.ok) {
                    resultMessage.innerHTML = `<div class="alert alert-success">${data.message}</div>`;

                    // 登園ステータスを保存
                    const storedStatus = loadAttendanceStatus();
                    selectedChildIds.forEach(childId => {
                        storedStatus[childId] = true; 
                    });
                    storedStatus['arrival_done'] = true;
                    saveAttendanceStatus(storedStatus);

                    updateButtonStates();
                } else {
                    resultMessage.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            } catch (error) {
                console.error('エラー (Arrival):', error);
                resultMessage.innerHTML = `<div class="alert alert-danger">エラーが発生しました。</div>`;
            }
        });

        // 降園ボタンが押されたとき
        departureBtn.addEventListener('click', async () => {
            const selectedChildIds = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            try {
                const response = await fetch('/api/attendance/departure', {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ children: selectedChildIds }),
                });

                const data = await response.json();

                if (response.ok) {
                    resultMessage.innerHTML = `<div class="alert alert-success">${data.message}</div>`;

                    // 降園ステータスを保存
                    const storedStatus = loadAttendanceStatus();
                        storedStatus['departure_done'] = true;
                        saveAttendanceStatus(storedStatus);

                        updateButtonStates();
                    } else {
                        resultMessage.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    }
                } catch (error) {
                    console.error('エラー (Departure):', error);
                    resultMessage.innerHTML = `<div class="alert alert-danger">エラーが発生しました。</div>`;
                }
            });

            // チェックボックスが変更された時にボタン状態を更新
            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', updateButtonStates);
            });

            // ページロード時にステータスを適用
            updateButtonStates();
        });
    </script>
@endsection