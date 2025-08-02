<?php
session_start();

// Jika session 'logged_in' tidak ada atau tidak bernilai true
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Tendang pengguna kembali ke halaman login
    header("Location: page1.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Search Engine</title>
    <link rel="stylesheet" href="CSS_First.css"> 
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<script>
    
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector('.search-bar');
    const input = form.querySelector('input');
    const imageGrid = document.querySelector('.image-grid');
    const trendingTitle = document.querySelector('.gallery-section h2');
    const themeToggle = document.getElementById('theme-toggle');
    const body = document.body;
    const sunIcon = themeToggle.querySelector('.bx-sun');
    const moonIcon = themeToggle.querySelector('.bx-moon');
    const themeText = themeToggle.querySelector('span');

    // Fungsi untuk menerapkan tema
const applyTheme = (theme) => {
    if (theme === 'dark') {
        body.classList.add('dark-mode');
        moonIcon.style.display = 'none';
        sunIcon.style.display = 'inline-block';
        themeText.textContent = 'Light';
    } else {
        body.classList.remove('dark-mode');
        moonIcon.style.display = 'inline-block';
        sunIcon.style.display = 'none';
        themeText.textContent = 'Night';
    }
};

// Cek tema yang tersimpan saat halaman dimuat
const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
    applyTheme(savedTheme);
}

// Event listener untuk tombol
themeToggle.addEventListener('click', () => {
    // Cek tema saat ini dan ganti
    const currentTheme = body.classList.contains('dark-mode') ? 'light' : 'dark';
    applyTheme(currentTheme);
    localStorage.setItem('theme', currentTheme); // Simpan pilihan ke localStorage
});
    
    // Ganti dengan API key Unsplash 
    const accessKey = "ppKBIaTFFpyw5EzQ2J-vuGBwF4wbegkxOJITddgLsVU"; 
    
    // Simpan HTML awal dari grid trending
    const initialTrendingHTML = imageGrid.innerHTML;

    // FUNGSI UNTUK MENAMPILKAN GAMBAR
    const fetchAndDisplayImages = async (query) => {
        // Jika tidak ada query, tampilkan kembali trending (tampilan awal)
        if (!query) {
            trendingTitle.textContent = 'Trending';
            imageGrid.innerHTML = initialTrendingHTML;
            return;
        }

        trendingTitle.textContent = `Results for: ${query}`;
        imageGrid.innerHTML = '<p>Loading images...</p>'; // Tampilkan pesan loading

        const url = `https://api.unsplash.com/search/photos?query=${query}&per_page=12&client_id=${accessKey}`;
        
        try {
            const response = await fetch(url);
            const data = await response.json();

            imageGrid.innerHTML = ''; // Kosongkan grid sebelum diisi

            if (data.results.length === 0) {
                imageGrid.innerHTML = '<p>No images found. Try another keyword.</p>';
                return;
            }

            data.results.forEach(photo => {
                const imageCard = document.createElement('div');
                imageCard.className = 'image-card';
                imageCard.innerHTML = `
                    <div class="card-image">
                        <img src="${photo.urls.small}" alt="${photo.alt_description || 'image'}">
                        <div class="image-overlay">
                            <i class='bx bx-slider-alt overlay-icon'></i>
                            <a href="${photo.links.download}?force=true" target="_blank" class="download-btn" title="Download">
                                <i class='bx bx-download'></i>
                            </a>
                            <a href="${photo.links.download}?force=true" target="_blank" class="download-btn" title="Download">
                                 <i class='bx bx-download'></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-info">
                        <h3>${photo.description || 'Untitled'}</h3>
                        <p>by ${photo.user.name}</p>
                    </div>
                `;
                imageGrid.appendChild(imageCard);
            });
        } catch (error) {
            imageGrid.innerHTML = '<p>Failed to load images. Please try again later.</p>';
            console.error("Error fetching images:", error);
        }
    };

    // EVENT SAAT FORM DI-SUBMIT (PENCARIAN)
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const query = input.value.trim();
        if (!query) return;

        // **LANGKAH 1: Tambahkan state baru ke history browser**
        // URL akan berubah menjadi "?query=..." tanpa me-reload halaman
        const newUrl = `?query=${encodeURIComponent(query)}`;
        history.pushState({ query: query }, '', newUrl);

        // Panggil fungsi untuk menampilkan hasil pencarian
        fetchAndDisplayImages(query);
    });

    // **LANGKAH 2: Dengarkan event 'popstate' (saat tombol back/forward ditekan)**
    window.addEventListener('popstate', (e) => {
        if (e.state && e.state.query) {
            // Jika ada state query, lakukan pencarian sesuai state tersebut
            input.value = e.state.query;
            fetchAndDisplayImages(e.state.query);
        } else {
            // Jika tidak ada state (kembali ke halaman awal), tampilkan trending
            input.value = '';
            fetchAndDisplayImages(null);
        }
    });
    // --- KODE BARU UNTUK FITUR UPLOAD GAMBAR ---

const uploadTrigger = document.getElementById('upload-btn-trigger');
const fileInput = document.getElementById('image-upload-input');

// 1. Saat tombol kamera diklik, picu input file yang tersembunyi
uploadTrigger.addEventListener('click', () => {
    fileInput.click();
});

// 2. Saat pengguna sudah memilih file
fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) {
        return; // Tidak ada file yang dipilih
    }

    // Tampilkan status loading kepada pengguna
    trendingTitle.textContent = `Analyzing Image: ${file.name}`;
    imageGrid.innerHTML = '<p>Finding similar images...</p>';

    // Panggil fungsi untuk memproses gambar
    searchByImage(file);
});

// 3. Fungsi untuk mengirim gambar ke backend (CONTOH)
async function searchByImage(imageFile) {
    // Membuat paket data untuk dikirim
    const formData = new FormData();
    formData.append('image', imageFile);

    // INI ADALAH BAGIAN KRITIS:
    // Di sini Anda akan mengirim formData ke alamat URL backend Anda.
    // Karena kita tidak punya backend, kita hanya akan simulasikan.
    
    console.log("Sending this image data to backend:", imageFile.name);
    
    // Simulasi: Tampilkan pesan bahwa fitur sedang dikembangkan
    setTimeout(() => {
         imageGrid.innerHTML = `<p>Reverse image search feature is not connected to a backend yet. But the image upload works!</p>`;
    }, 1500);
}

    // Cek saat halaman pertama kali dimuat, apakah ada query di URL
    const initialQuery = new URLSearchParams(window.location.search).get('query');
    if (initialQuery) {
        input.value = initialQuery;
        fetchAndDisplayImages(initialQuery);
    }
});
</script>
<body>
    
    <div class="page-container">
        <header class="main-header">
            <div class="logo">
                <i class='bx bx-image-alt'></i>
                <span>Image search</span>
            </div>

            <nav class="main-nav">
                 <button id="theme-toggle" class="theme-toggle-btn">
                    <i class='bx bx-moon'></i> <i class='bx bx-sun' style="display: none;"></i> <span>Night</span>
                </button>
                 <a href="#">Optics</a>
                 <a href="#" class="nav-button">Search</a>
            </nav>

            <div class="user-profile">
                <img src="https://i.pravatar.cc/40" alt="User Avatar">
                <span class="notification-dot"></span>
            </div>
        </header>

        <main class="main-content">
            <section class="search-section">
                <h1>Find Your Image</h1>
                
                <div class="search-bar-wrapper">
    <form id="searchForm" class="search-bar" action="#" method="get">
        
        <input type="file" id="image-upload-input" accept="image/*" style="display: none;">

        <input type="text" placeholder="Search for anything...">
        
        <button type="button" id="upload-btn-trigger" class="upload-btn" title="Search by image">
            <i class='bx bx-camera'></i>
        </button>
        
        <button type="submit" class="search-btn" title="Search">
            <i class='bx bx-search'></i>
        </button>
        </form>
    </div>

                <div class="page-indicators">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </section>

            <section class="gallery-section">
                <h2>Trending</h2>
                <div class="image-grid">
                    <div class="image-card">
                        <div class="card-image">
                            <img src="Hat.jpg" alt=" hat">
                            <div class="image-overlay">
                                <i class='bx bx-slider-alt overlay-icon'></i>
                                <a href="Hat.jpg" download class="download-btn" title="Download">
                                    <i class='bx bx-download'></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-info">
                            <h3>Portrait</h3>
                            <p>Style</p>
                        </div>
                    </div>
                    <div class="image-card">
                         <div class="card-image">
                            <img src="Beach.jpg" alt="Beach">
                            <div class="image-overlay">
                                <i class='bx bx-slider-alt overlay-icon'></i>
                                <a href="Beach.jpg" download class="download-btn" title="Download">
                                    <i class='bx bx-download'></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-info">
                            <h3>Lifestyle</h3>
                            <p>Nature</p>
                        </div>
                    </div>
                    <div class="image-card">
                         <div class="card-image">
                            <img src="Modern_Room_Living.jpg" alt="Modern living room">
                            <div class="image-overlay">
                                <i class='bx bx-slider-alt overlay-icon'></i>
                                <a href="Modern_Room_Living.jpg" download class="download-btn" title="Download">
                                    <i class='bx bx-download'></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-info">
                            <h3>Interior</h3>
                            <p>Home</p>
                        </div>
                    </div>
                    <div class="image-card">
                         <div class="card-image">
                            <img src="Abstract_Green.jpg" alt="Abstract green pattern">
                            <div class="image-overlay">
                                <i class='bx bx-slider-alt overlay-icon'></i>
                                <a href="Abstract_Green.jpg" download class="download-btn" title="Download">
                                    <i class='bx bx-download'></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-info">
                            <h3>Abstract</h3>
                            <p>Creative</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
    
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.querySelector('.search-bar');
        const input = form.querySelector('input');
        const imageGrid = document.querySelector('.image-grid');
        const accessKey = "ppKBIaTFFpyw5EzQ2J-vuGBwF4wbegkxOJITddgLsVU"; // Ganti dengan API key kamu

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const query = input.value.trim();
            if (!query) return;

            const url = `https://api.unsplash.com/search/photos?query=${query}&per_page=9&client_id=${accessKey}`;
            const response = await fetch(url);
            const data = await response.json();

            imageGrid.innerHTML = '';

            data.results.forEach(photo => {
                const imageCard = document.createElement('div');
                imageCard.className = 'image-card';
                imageCard.innerHTML = `
                    <div class="card-image">
                        <img src="${photo.urls.small}" alt="${photo.alt_description || 'image'}">
                        <div class="image-overlay">
                            <i class='bx bx-slider-alt overlay-icon'></i>
                            <a href="${photo.links.download}" download class="download-btn" title="Download">
                                <i class='bx bx-download'></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-info">
                        <h3>${photo.description || 'Untitled'}</h3>
                        <p>${photo.user.name}</p>
                    </div>
                `;
                imageGrid.appendChild(imageCard);
            });
        });
    });
</script>
</body>
</html>