<div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
    <h3 class="mt-4">基本情報</h3>
    @if ($children->isEmpty())
        <p>登録された子供の情報がありません。</p>
    @else
        @foreach ($children as $child)
        <div class="card mb-3">
            <div class="card-body d-flex align-items-center">
                <h5 class="card-title mr-3">{{ $child->last_name }} {{ $child->first_name }}</h5>

                <img src="{{ $child->image ? asset('storage/children/' . $child->image) : asset('images/default-image.jpg') }}" 
                     class="rounded-circle" width="50" height="50" alt="child image">
            </div>
                <p class="card-text ml-3"><strong>クラス:</strong> {{ $child->classroom->name  ?? '未登録' }}</p>
                <p class="card-text"><strong>生年月日:</strong> {{ $child->birthdate }}</p>
                <p class="card-text"><strong>入園日:</strong> {{ $child->admission_date }}</p>
                <p class="card-text"><strong>アレルギー:</strong> {{ $child->has_allergy ? $child->allergy_type : 'なし' }}</p>
                <p class="card-text"><strong>既往歴:</strong> {{ $child->medical_history }}</p>
            </div>
        </div>
        @endforeach
    @endif
</div>