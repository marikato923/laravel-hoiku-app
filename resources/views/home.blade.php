@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 500px; margin: 0 auto;">
        <h2 class="text-center mb-4">登園・降園チェック</h2>
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

        function fadeOutMessage(element) {
            setTimeout(() => {
                element.style.transition = "opacity 1s ease-out, transform 1s ease-out, margin-bottom 0.5s ease-out";
                element.style.opacity = "0";
                element.style.transform = "translateY(10px)";
                element.style.marginBottom = "0px";

                setTimeout(() => {
                    element.style.display = "none";
                }, 1000);
            }, 3000);
        }

        function updateButtonStates() {
            const selectedChildIds = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            const storedStatus = loadAttendanceStatus();
            let canArrive = selectedChildIds.length > 0;
            let canDepart = selectedChildIds.length > 0;

            selectedChildIds.forEach(childId => {
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
            pickupTime.disabled = !canArrive; // 修正: arrivalBtn と連動
            departureBtn.disabled = !canDepart;

            arrivalBtn.classList.toggle('btn-secondary', !canArrive);
            arrivalBtn.classList.toggle('arrival-btn', canArrive);
            departureBtn.classList.toggle('btn-secondary', !canDepart);
            departureBtn.classList.toggle('departure-btn', canDepart);
        }

        async function sendAttendance(type, bodyData) {
            const url = type === 'arrival' ? '/api/attendance/arrival' : '/api/attendance/departure';
            const button = type === 'arrival' ? arrivalBtn : departureBtn;

            button.disabled = true;  // 二重クリック防止

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(bodyData),
                });

                const data = await response.json();

                if (response.ok) {
                    resultMessage.innerHTML = `<div class="alert alert-success">${Array.isArray(data.messages) ? data.messages.join('<br>') : data.message}</div>`;
                    fadeOutMessage(resultMessage.firstElementChild);

                    const storedStatus = loadAttendanceStatus();
                    bodyData.children.forEach(childId => {
                        if (!storedStatus[childId]) {
                            storedStatus[childId] = {};
                        }
                        if (type === 'arrival') {
                            storedStatus[childId].arrived = true;
                            storedStatus[childId].departed = false;
                        } else {
                            storedStatus[childId].departed = true;
                        }
                    });

                    saveAttendanceStatus(storedStatus);
                    updateButtonStates();
                } else {
                    resultMessage.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    fadeOutMessage(resultMessage.firstElementChild);
                    button.disabled = false;  // エラー時はボタンを再度有効化
                }
            } catch (error) {
                resultMessage.innerHTML = `<div class="alert alert-danger">エラーが発生しました。</div>`;
                fadeOutMessage(resultMessage.firstElementChild);
                button.disabled = false;  // エラー時はボタンを再度有効化
            }
        }

        arrivalBtn.addEventListener('click', () => {
            const selectedChildIds = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            const bodyData = { children: selectedChildIds };

            // pickup_time が入力されている場合のみ送信
            if (pickupTime.value) {
                bodyData.pickup_time = pickupTime.value;
            }

            sendAttendance('arrival', bodyData);

            // 登園後に pickup_time を無効化
            pickupTime.disabled = true;
        });

        departureBtn.addEventListener('click', () => {
            const selectedChildIds = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            sendAttendance('departure', { children: selectedChildIds });
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateButtonStates);
        });

        updateButtonStates();
    });
   </script>
@endsection