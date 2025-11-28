<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Fonnte - Klinik Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>🧪 Test Notifikasi WhatsApp</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                ✅ {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                ❌ {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('test.fonnte.send') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor WhatsApp</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="phone" 
                                       name="phone" 
                                       value="6285167655225" 
                                       required>
                                <div class="form-text">Format: 6285167655225</div>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label>
                                <textarea class="form-control" 
                                          id="message" 
                                          name="message" 
                                          rows="4" 
                                          required>🎉 Test notifikasi dari Laravel!\n\nIni test sederhana dalam folder Laravel.</textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    📨 Kirim Test Notifikasi
                                </button>
                                <a href="{{ route('test.fonnte.auto') }}" class="btn btn-outline-secondary">
                                    🔄 Test Otomatis
                                </a>
                            </div>
                        </form>

                        <hr>

                        <div class="mt-3">
                            <h6>Info Token:</h6>
                            <code>Cd7HTvU8q8ZsDGhdAmST</code>
                            <p class="text-muted small mt-2">
                                Token: <strong>{{ substr('Cd7HTvU8q8ZsDGhdAmST', 0, 10) }}...</strong><br>
                                Target: <strong>6285167655225</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>