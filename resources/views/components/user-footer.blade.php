<footer style="width: 100%; background-color: rgb(255, 195, 195); padding: 20px 10px; text-align: center; border-top: 1px solid rgb(255, 195, 195); color: #555;">
    <div style="margin-bottom: 10px;">
        {{-- 園について --}}
        <a href="{{ route('kindergarten.show') }}" style="color: #666; text-decoration: none; margin: 0 10px;">園について</a>
        <span style="color: #aaa;">|</span>
        {{-- 利用規約 --}}
        <a href="{{ route('terms.show') }}" style="color: #666; text-decoration: none; margin: 0 10px;">利用規約</a>
    </div>
    <p>&copy; {{ date('Y') }} Kodomo Log All rights reserved.</p>
</footer>
