// =====================
// Fitur search kamar
// =====================

// Ambil input search
const searchInput = document.getElementById("searchInput");

// Ambil semua item kamar
const roomItems = document.querySelectorAll(".room-item");

// Text jika tidak ada hasil
const noResultText = document.getElementById("noResultText");

// Jalankan hanya jika elemen search ada (biar aman di halaman lain)
if (searchInput) {
  searchInput.addEventListener("keyup", function () {
    // Ambil keyword dari user dan ubah ke lowercase
    const keyword = searchInput.value.toLowerCase();

    // Hitung jumlah kamar yang ditampilkan
    let visibleRoomCount = 0;

    roomItems.forEach(function (room) {
      // Ambil keyword dari data-name tiap kamar
      const roomName = room.getAttribute("data-name").toLowerCase();

      // Cek apakah keyword cocok
      if (roomName.includes(keyword)) {
        room.style.display = "";
        visibleRoomCount++;
      } else {
        room.style.display = "none";
      }
    });

    // Tampilkan pesan jika tidak ada hasil
    if (noResultText) {
      if (visibleRoomCount === 0) {
        noResultText.classList.remove("d-none");
      } else {
        noResultText.classList.add("d-none");
      }
    }
  });
}

// =====================
// Fitur validasi form booking
// =====================

// Ambil form dan hasil output
const bookingForm = document.getElementById("bookingForm");
const bookingResult = document.getElementById("bookingResult");

// Ambil semua input
const guestName = document.getElementById("guestName");
const guestContact = document.getElementById("guestContact");
const roomType = document.getElementById("roomType");
const roomNumber = document.getElementById("roomNumber");
const checkIn = document.getElementById("checkIn");
const checkOut = document.getElementById("checkOut");
const guestCount = document.getElementById("guestCount");

// Menampilkan pesan error di bawah input
function showError(input, message) {
  const errorText = document.getElementById(input.id + "Error");

  if (errorText) {
    errorText.textContent = message;
  }
}

// Menghapus pesan error
function clearError(input) {
  const errorText = document.getElementById(input.id + "Error");

  if (errorText) {
    errorText.textContent = "";
  }
}

// Validasi satu input
function validateInput(input, message) {
  if (!input.value) {
    showError(input, message);
    return false;
  }

  clearError(input);
  return true;
}

// Validasi seluruh form
function validateBookingForm() {
  let isValid = true;

  if (!validateInput(guestName, "Nama lengkap wajib diisi.")) isValid = false;
  if (!validateInput(guestContact, "Email atau nomor HP wajib diisi.")) isValid = false;
  if (!validateInput(roomType, "Tipe kamar wajib dipilih.")) isValid = false;
  if (!validateInput(roomNumber, "Nomor kamar wajib dipilih.")) isValid = false;
  if (!validateInput(checkIn, "Tanggal check-in wajib diisi.")) isValid = false;
  if (!validateInput(checkOut, "Tanggal check-out wajib diisi.")) isValid = false;
  if (!validateInput(guestCount, "Jumlah tamu wajib diisi.")) isValid = false;

  // Validasi logika tanggal
  if (checkIn.value && checkOut.value && checkOut.value <= checkIn.value) {
    showError(checkOut, "Tanggal check-out harus setelah tanggal check-in.");
    isValid = false;
  }

  return isValid;
}

// Jalankan hanya jika form ada
if (bookingForm) {
  const bookingInputs = [
    guestName,
    guestContact,
    roomType,
    roomNumber,
    checkIn,
    checkOut,
    guestCount
  ];

  // Validasi realtime saat user input
  bookingInputs.forEach(function (input) {
    if (input) {
      input.addEventListener("input", validateBookingForm);
      input.addEventListener("change", validateBookingForm);
    }
  });

  // Saat form disubmit
  bookingForm.addEventListener("submit", function (event) {
    event.preventDefault();

    // Cek validasi
    if (!validateBookingForm()) {
      return;
    }

    // Tampilkan hasil booking
    bookingResult.classList.remove("d-none");
    bookingResult.innerHTML = `
      <strong>Booking berhasil!</strong><br>
      Terima kasih, ${guestName.value}. Reservasi untuk ${roomNumber.value} dari tanggal ${checkIn.value} sampai ${checkOut.value} telah tercatat.
    `;

    // Reset form setelah submit
    bookingForm.reset();

    // Hapus semua error setelah reset
    bookingInputs.forEach(function (input) {
      clearError(input);
    });
  });
}