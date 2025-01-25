@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="mb-4">園児の詳細情報</h2>

        <div class="card">
            <div class="card-header">
                {{ $child->last_name }} {{ $child->first_name }} ({{ $child->last_kana_name }} {{ $child->first_kana_name }})
            </div>
            <div class="card-body">
                <p><strong>生年月日:</strong> {{ $child->birthdate }}</p>
                <p><strong>既往歴:</strong> {{ $child->medical_history ?: 'なし' }}</p>
                <p><strong>アレルギー:</strong> {{ $child->has_allergy ? 'あり (' . $child->allergy_type . ')' : 'なし' }}</p>
                <p><strong>ステータス:</strong>
                    @if ($child->status === 'approved')
                        <span class="badge bg-success">承認済み</span>
                    @elseif ($child->status === 'pending')
                        <span class="badge bg-warning">承認待ち</span>
                    @else
                        <span class="badge bg-danger">却下</span>
                    @endif
                </p>

                {{-- 保護者情報 --}}
                <p><strong>保護者:</strong>
                    @if ($child->user)
                        {{ $child->user->last_name }} {{ $child->user->first_name }} (ID: {{ $child->user->id }})
                    @else
                        <span class="text-muted">未登録</span>
                    @endif
                </p>


                {{-- クラス編集へのリンク --}}
                <div class="mt-4">
                    <a href="{{ route('admin.children.edit', $child->id) }}" class="btn btn-primary">
                        編集
                    </a>
                </div>

                {{-- 承認・却下ボタン --}}
                @if ($child->status === 'pending')
                    <div class="mt-4 d-flex">
                        <form action="{{ route('admin.children.approve', $child->id) }}" method="POST" class="me-2">
                            @csrf
                            <button type="submit" class="btn btn-success">承認</button>
                        </form>

                        {{-- 却下モーダルをトリガー --}}
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            却下
                        </button>
                    </div>
                @endif
            </div>
        </div>
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
                        <button type="submit" class="btn btn-danger">却下する</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
