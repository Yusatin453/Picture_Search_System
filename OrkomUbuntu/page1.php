<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Image Search</title>
    <link rel="stylesheet" href="styleIMG.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="visual-side">
            <div class="visual-content">
                <div class="logo">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM10 16.5V7.5L16 12L10 16.5Z" fill="white"/>
                    </svg>
                    <span>Image Search</span>
                </div>
                <div class="main-text">
                    <h1>Temukan Inspirasi Visual Anda.</h1>
                    <p>Jutaan gambar bebas royalti menanti.</p>
                </div>
            </div>
        </div>

        <div class="form-side">
            <div class="form-container">
                <h2>Selamat Datang Kembali!</h2>
                <p class="subtitle">Silakan masuk untuk melanjutkan.</p>
                
                <form action="proses_login.php" method="POST">
                    <div class="input-group">
                    <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" required>
                </div>

                <div class="input-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <a href="#" class="forgot-password">Lupa kata sandi?</a>

                <button type="submit" class="btn btn-primary">Masuk</button>
                </form>

                <div class="separator">
                    <span>ATAU</span>
                </div>

                <button type="button" class="btn btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.64 9.20455C17.64 8.56636 17.5832 7.95273 17.4727 7.36364H9V10.845H13.8436C13.635 11.9705 13.0009 12.9232 12.0477 13.5614V15.8195H14.9564C16.6582 14.2527 17.64 11.9459 17.64 9.20455Z" fill="#4285F4"/>
                        <path d="M9 18C11.43 18 13.4673 17.1941 14.9564 15.8195L12.0477 13.5614C11.2418 14.1014 10.2109 14.4205 9 14.4205C6.96182 14.4205 5.23727 13.0218 4.65955 11.1818H1.74545V13.5082C3.23273 16.2059 5.89091 18 9 18Z" fill="#34A853"/>
                        <path d="M4.65955 11.1818C4.44409 10.5682 4.32273 9.90227 4.32273 9.20455C4.32273 8.50682 4.44409 7.84091 4.65955 7.22727V4.9C3.23273 7.59773 3.23273 10.8114 4.65955 11.1818Z" fill="#FBBC05"/>
                        <path d="M9 3.57955C10.3214 3.57955 11.5077 4.03364 12.4405 4.92545L15.0218 2.34409C13.4673 0.891818 11.43 0 9 0C5.89091 0 3.23273 1.79409 1.74545 4.9L4.65955 7.22727C5.23727 5.38727 6.96182 3.57955 9 3.57955Z" fill="#EA4335"/>
                    </svg>
                    Masuk dengan Google
                </button>

                <p class="signup-link">
                    Belum punya akun? <a href="registrasi.html">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>