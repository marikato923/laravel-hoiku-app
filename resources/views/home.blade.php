@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 500px; margin: 0 auto;">
        <h2 class="text-center mb-4">打刻</h2>
        <hr class="mb-4">

        @if ($children->isEmpty())
            <div class="text-center">
                <p>まだお子様が登録されていません。</p>
                <a href="{{ route('children.create') }}" class="btn register-btn mt-3">新規登録</a>
            </div>
        @else
            <form id="attendanceForm" class="mx-auto" style="max-width: 400px;">
                @csrf

                <!-- 子供のリストとチェックボックス -->
                <div class="mb-3">
                    <label class="form-label">お子様を選択してください</label>
                    @foreach ($children as $child)
                        <div class="form-check">
                            <input class="form-check-input child-checkbox" type="checkbox" id="child_{{ $child->id }}" name="children[]" value="{{ $child->id }}">
                            <label class="form-check-label" for="child_{{ $child->id }}">
                                {{ $child->last_name }} {{ $child->first_name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <!-- 登園用フォーム -->
                <div class="mb-3">
                    <label for="pickup_time" class="form-label">お迎え予定時刻</label>
                    <input type="time" class="form-control" id="pickup_time" name="pickup_time" required disabled>
                </div>

                <!-- ボタン -->
                <div class="text-end mt-4">
                    <button type="button" class="btn arrival-btn me-2" id="arrivalBtn" disabled
                        style="font-size: 1.2em">登園
                    </button>
                    <button type="button" class="btn departure-btn" id="departureBtn" disabled
                        style="font-size: 1.2em">降園
                    </button>
                </div>
            </form>

            <div id="resultMessage" class="mt-3 text-center"></div>
        @endif
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('.child-checkbox');
        const arrivalBtn = document.getElementById('arrivalBtn');
        const departureBtn = document.getElementById('departureBtn');
        const pickupTime = document.getElementById('pickup_time');
        const resultMessage = document.getElementById('resultMessage');

        function getTodayKey() {
            return `attendance_status_${new Date().toISOString().split('T')[0]}`;
        }

        function loadAttendanceStatus() {
            return JSON.parse(localStorage.getItem(getTodayKey())) || {};
        }

        function saveAttendanceStatus(status) {
            localStorage.setItem(getTodayKey(), JSON.stringify(status));
        }

        function updateButtonStates() {
            const selectedChildIds = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            const storedStatus = loadAttendanceStatus();
            let canArrive = selectedChildIds.length > 0;
            let canDepart = selectedChildIds.length > 0;

            selectedChildIds.forEach(childId => {
                if (!selectedChildIds.length) {
                    canArrive = false;
                    canDepart = false;
                }
                if (storedStatus[childId]?.arrived) {
                    canArrive = false;
                } else {
                    canDepart = false;
                }
                if (storedStatus[childId]?.departed) {
                    canDepart = false;
                }
            });

            arrivalBtn.disabled = !canArrive;
            pickupTime.disabled = !canArrive;
            departureBtn.disabled = !canDepart;

            arrivalBtn.classList.toggle('btn-secondary', !canArrive);
            arrivalBtn.classList.toggle('arrival-btn', canArrive);
            departureBtn.classList.toggle('btn-secondary', !canDepart);
            departureBtn.classList.toggle('departure-btn', canDepart);
        }

        arrivalBtn.addEventListener('click', async () => {
            const selectedChildIds = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            if (!pickupTime.value) {
                resultMessage.innerHTML = `<div class="alert alert-danger">お迎え予定時刻を入力してください。</div>`;
                return;
            }

            try {
                const response = await fetch('/api/attendance/arrival', {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ children: selectedChildIds, pickup_time: pickupTime.value }),
                });

                const data = await response.json();

                if (response.ok) {
                    resultMessage.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    const storedStatus = loadAttendanceStatus();
                    selectedChildIds.forEach(childId => {
                        storedStatus[childId] = { arrived: true, departed: false };
                    });
                    saveAttendanceStatus(storedStatus);
                    updateButtonStates();
                    pickupTime.disabled = true; 
                } else {
                    resultMessage.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            } catch (error) {
                console.error('エラー (Arrival):', error);
                resultMessage.innerHTML = `<div class="alert alert-danger">エラーが発生しました。</div>`;
            }
        });

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
                    resultMessage.innerHTML = `<div class="alert alert-success">${data.messages.join('<br>')}</div>`;
                    const storedStatus = loadAttendanceStatus();
                    selectedChildIds.forEach(childId => {
                        if (storedStatus[childId]) storedStatus[childId].departed = true;
                    });
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

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateButtonStates);
        });

        updateButtonStates();
    });
   </script>
@endsection