@extends('layouts.app')

@section('title', 'Kirim Surat')

@section('content')
    <style>
        .form-panel { max-width: 560px; }
        .form-panel label { display: block; margin-bottom: 6px; font-size: 14px; color: #334155; }
        .form-panel input[type=text], .form-panel input[type=date], .form-panel input[type=file] {
            width: 100%; padding: 10px 12px; margin-bottom: 16px;
            border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #fff;
        }
        .form-panel .hint { margin: -10px 0 16px; font-size: 12px; color: #64748b; }
        .form-panel .actions { display: flex; gap: 10px; }
        .btn { display: inline-block; padding: 10px 18px; background: #1e3a8a; color: #fff; border: 0; border-radius: 8px; font-size: 14px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #1e40af; }
        .btn-light { background: #e2e8f0; color: #334155; }
        .btn-light:hover { background: #cbd5e1; }
        .alert-error { margin-bottom: 16px; padding: 10px 14px; background: #fee2e2; color: #b91c1c; border-radius: 8px; font-size: 14px; }
        .alert-error ul { margin: 0; padding-left: 18px; }
    </style>

    <h1>Kirim Surat</h1>

    <div class="panel form-panel">
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/pengirim/surat" enctype="multipart/form-data">
            @csrf

            <label for="nomor_surat">Nomor surat (opsional)</label>
            <input type="text" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat') }}">

            <label for="perihal">Perihal</label>
            <input type="text" id="perihal" name="perihal" value="{{ old('perihal') }}" required>

            <label for="tanggal_surat">Tanggal surat</label>
            <input type="date" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required>

            <label for="file_surat">File surat</label>
            <input type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" required>
            <div class="hint">Format PDF, JPG, atau PNG. Maksimal 5 MB.</div>

            <div class="actions">
                <button type="submit" class="btn">Kirim</button>
                <a href="/pengirim/surat" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
@endsection