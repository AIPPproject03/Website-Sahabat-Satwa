// Menampilkan animasi loading saat tombol ditekan
document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll("button, .btn");
  const loading = document.createElement("div");
  loading.className = "loading";
  loading.innerText = "Loading...";
  document.body.appendChild(loading);

  buttons.forEach((button) => {
    button.addEventListener("click", function () {
      loading.style.display = "block";
      setTimeout(() => {
        loading.style.display = "none";
      }, 1000); // Simulasi waktu loading 1 detik
    });
  });

  // Menampilkan pop-up info saat mouse hover
  const menuItems = document.querySelectorAll(".menu-item");

  menuItems.forEach((item) => {
    const popup = item.querySelector(".popup-info");

    // Tambahkan event listener untuk menu item
    item.addEventListener("mouseenter", () => {
      if (popup) {
        popup.style.opacity = "1";
        popup.style.transform = "translateX(0)";
        popup.style.pointerEvents = "auto";
      }
    });

    item.addEventListener("mouseleave", (e) => {
      // Pastikan kursor tidak berada di dalam pop-up sebelum menyembunyikan
      if (popup && !popup.contains(e.relatedTarget)) {
        popup.style.opacity = "0";
        popup.style.transform = "translateX(-100%)";
        popup.style.pointerEvents = "none";
      }
    });

    // Tambahkan event listener untuk pop-up
    if (popup) {
      popup.addEventListener("mouseenter", () => {
        popup.style.opacity = "1";
        popup.style.transform = "translateX(0)";
        popup.style.pointerEvents = "auto";
      });

      popup.addEventListener("mouseleave", (e) => {
        // Pastikan kursor tidak kembali ke menu item sebelum menyembunyikan
        if (!item.contains(e.relatedTarget)) {
          popup.style.opacity = "0";
          popup.style.transform = "translateX(-100%)";
          popup.style.pointerEvents = "none";
        }
      });
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  // Buat elemen loading
  const loading = document.createElement("div");
  loading.className = "loading";
  loading.innerHTML = `
    <div class="loading-text">
      <span>L</span>
      <span>o</span>
      <span>a</span>
      <span>d</span>
      <span>i</span>
      <span>n</span>
      <span>g</span>
    </div>
    <div class="loading-dots">
      🐾 🐶 🐱
    </div>
  `;
  document.body.appendChild(loading);

  // Tambahkan event listener untuk semua tautan
  const links = document.querySelectorAll("a");
  links.forEach((link) => {
    link.addEventListener("click", function (e) {
      // Cegah navigasi langsung
      e.preventDefault();

      // Tampilkan animasi loading
      loading.style.display = "flex";

      setTimeout(() => {
        window.location.href = link.href;
      }, 500);
    });
  });

  // Tambahkan event listener untuk tombol dengan kelas tertentu
  const buttons = document.querySelectorAll(
    "a.btn-edit, a.btn-delete, a.btn-add"
  );
  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault(); // Mencegah navigasi langsung
      loading.style.display = "flex";

      // Simulasi waktu loading sebelum navigasi
      setTimeout(() => {
        window.location.href = button.href; // Navigasi ke tautan
      }, 2000); // 2 detik loading
    });
  });
});

// Animasi hover pada tabel
const tableRows = document.querySelectorAll("table tr");
tableRows.forEach((row) => {
  row.addEventListener("mouseover", () => {
    row.style.transition = "background-color 0.3s ease";
  });
  row.addEventListener("mouseout", () => {
    row.style.transition = "background-color 0.3s ease";
  });
});
