@if ($errors->any())
    <div class="alert alert-error alert-soft shadow-sm text-left" role="alert">
        <div>
            <p class="text-sm font-semibold">Periksa kembali isian form Anda.</p>
            <ul class="mt-1 list-disc space-y-0.5 pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
