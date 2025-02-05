@extends('layouts.admin')

@section('breadcrumbs')
    <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">ホーム</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.children.index') }}">園児一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">園児の情報</li>
        </ol>
    </nav>
@endsection

@section('content')
        <div class="container">
                <div class="text-center mb-4">
                    <h1 class="mb-3" style="font-weight: normal;"><span style="font-size: 0.8em;">園児の情報 :</span> {{ $child->last_name }} {{ $child->first_name }}</h1>
                </div>

                <div class="text-center mb-4 py-2">
                    {{-- 子供の画像 --}}
                    @php
                        $themeColor = optional($child->classroom)->theme_color ?? '#ccc'; 
                    @endphp
                    <a href="{{ route('admin.attendance.show', $child->id) }}">
                        <div class="child-img-wrapper" style="border-color:{{ $themeColor }}; width: 150px; height: 150px; display: inline-block;">
                            @if($child->img)
                                <img src="{{ asset('storage/children/' . $child->img) }}" alt="子供の写真" class="child-img-index">
                            @else
                                <img src="{{ asset('storage/children/default.png') }}" alt="デフォルトの写真" class="child-img-index">
                            @endif
                        </div>
                    </a>
                </div>

                <ul class="list-unstyled mx-auto" style="max-width: 500px;">
                    <li class="mb-3"><strong>氏名:</strong> {{ $child->last_name }} {{ $child->first_name }}</li>
                    <li class="mb-3"><strong>フリガナ:</strong> {{ $child->last_kana_name }} {{ $child->first_kana_name }}</li>
                    <li class="mb-3"><strong>生年月日:</strong> {{ $child->birthdate }}</li>
                    <li class="mb-3"><strong>既往歴:</strong> {{ $child->medical_history ?: 'なし' }}</li>
                    <li class="mb-3"><strong>アレルギー:</strong> {{ $child->has_allergy ? 'あり (' . $child->allergy_type . ')' : 'なし' }}</li>
                    <li class="mb-3"><strong>ステータス:</strong>
                        @if ($child->status === 'approved')
                            <span class="badge bg-success">承認済み</span>
                        @elseif ($child->status === 'pending')
                            <span class="badge bg-warning">承認待ち</span>
                        @else
                            <span class="badge bg-danger">却下</span>
                        @endif
                    </li>
                    <li class="mb-3"><strong>保護者:</strong>
                        @if ($child->user)
                            {{ $child->user->last_name }} {{ $child->user->first_name }} (ID: {{ $child->user->id }})
                        @else
                            <span class="text-muted">未登録</span>
                        @endif
                    </li>
                </ul>

                <div class="mt-4 d-flex justify-content-end align-items-center gap-3" style="max-width: 500px; margin: 0 auto;">
                    @if ($child->status === 'pending')
                        <form action="{{ route('admin.children.approve', $child->id) }}" method="POST">
                            @csrf
                            <div class="d-flex gap-3">
                                <label class="radio-label">
                                    <input type="radio" name="status" value="approved" {{ $child->status === 'approved' ? 'checked' : '' }} onchange="this.form.submit()"> 承認
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="status" value="rejected" {{ $child->status === 'rejected' ? 'checked' : '' }} data-bs-toggle="modal" data-bs-target="#rejectModal"> 却下
                                </label>
                            </div>
                        </form>
                    @endif
                    <a href="{{ route('admin.children.edit', $child->id) }}" class="btn register-btn" style="white-space: nowrap;">
                        編集
                    </a>
                </div>
                    
            {{-- 却下理由モーダル --}}
            <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.children.reject', $child->id) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="rejectModalLabel">却下理由を入力</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="rejection_reason">却下理由</label>
                                    <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn delete-btn">却下する</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>  

<style>
.radio-label input[type="radio"] {
    accent-color: rgb(255, 175, 175);
}
</style>

@endsection
