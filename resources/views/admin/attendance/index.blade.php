@extends('layouts.admin')

@section('breadcrumbs')
    <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">ホーム</a></li>
            <li class="breadcrumb-item active" aria-current="page">出席状況一覧</li>
        </ol>
    </nav>
@endsection

@section('content')
        <div class="container py-2">
            {{-- 選択された日付をフォーマットして表示 --}}
        @php
            use Carbon\Carbon;
            $formattedDate = Carbon::parse($date)->isoFormat('YYYY年M月D日(ddd)');
        @endphp

        <h1 class="mb-5 text-center" style="text-align: center; width: 100%;">
            <span style="font-size: 0.8em;">出席状況 :</span> {{ $formattedDate }}
        </h1>

            {{-- クラス選択と日付変更フォーム --}}
            <form method="GET" action="{{ route('admin.attendance.index') }}" class="mb-5 attendance-search-form">
                <div class="row justify-content-center align-items-start">
                    {{-- クラス選択 --}}
                    <div class="col-md-4 mb-3 d-flex flex-column">
                        <label for="classroom_id" class="font-weight-bold">クラス:</label>
                        <select name="classroom_id" id="classroom_id" class="form-control attendance-form-control">
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ $classroom->id == $classroomId ? 'selected' : '' }}>
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                
                    {{-- 日付選択 --}}
                    <div class="col-md-4 mb-3 d-flex flex-column">
                        <label for="date" class="font-weight-bold">日付:</label>
                        <input type="date" name="date" id="date" class="form-control attendance-form-control" value="{{ $date }}">
                    </div>
                
                    {{-- 検索ボタン --}}
                    <div class="col-md-4 d-flex justify-content-end align-items-center">
                        <button type="submit" class="btn attendance-btn px-4 mt-4">検索</button>
                    </div>                                   
                </div>      
            </form>

            {{-- 出席状況 --}}
            @if (empty($groupedAttendances))
                <div class="alert alert-info text-center">該当するデータがありません。</div>
            @else
                <table class="table table-striped table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>園児名</th>
                            <th>登園時間</th>
                            <th>降園時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedAttendances as $attendance)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.attendance.show', ['childId' => $attendance['child']->id]) }}" class="child-link">
                                        {{ $attendance['child']->last_name }} {{ $attendance['child']->first_name }}
                                    </a>
                                </td>
                                <td>
                                    {{ $attendance['arrival_time'] !== '未登録' ? \Carbon\Carbon::parse($attendance['arrival_time'])->format('H:i') : '未登録' }}
                                </td>
                                <td>
                                    {{ $attendance['departure_time'] !== '未登録' ? \Carbon\Carbon::parse($attendance['departure_time'])->format('H:i') : '未登録' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
@endsection

{{-- スタイル --}}
<style>
    /* コンテナ全体の余白調整 */
    .container {
        max-width: 900px;
    }

    /* フォーム全体 */
    .attendance-search-form {
        max-width: 700px;
        margin: 0 auto;
    }

    /* フォーム各要素 */
    .attendance-form-control {
        border-radius: 5px;
    }

    /* フォーム内のレイアウト調整 */
    .row {
        display: flex;
        flex-wrap: wrap; /* 画面幅が狭いときに縦並びに */
        align-items: flex-start;
    }

    /* 各列のスタイル */
    .col-md-4 {
        flex: 0 0 100%; /* デフォルトでは1列で配置 */
        max-width: 100%;
        margin-bottom: 15px;
    }

    /* ボタンが折りたたまれた場合でも右側に残る */
    @media (min-width: 768px) {
        .col-md-4 {
            flex: 0 0 33.333%; /* 横並びに調整 */
            max-width: 33.333%;
        }

        .col-md-4:last-child {
            justify-content: flex-end;
        }
    }

    /* ボタンのデザイン */
    .attendance-search-button {
        background-color: rgb(255, 175, 175) !important;
        color: white !important;
        border: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .attendance-search-button:hover {
        background-color: rgb(255, 150, 150);
    }

    /* 子供の名前リンクのスタイル */
    .child-link {
        color: rgb(200, 100, 100);
        text-decoration: none;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .child-link:hover {
        color: rgb(255, 175, 175);
    }

    table th, table td {
        vertical-align: middle;
        padding: 12px;
    }

    h1 {
        font-size: 1.75rem;
        font-weight: bold;
        color: #333;
    }
</style>
