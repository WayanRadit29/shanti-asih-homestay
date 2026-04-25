const searchInput = document.getElementById("searchInput");
const roomItems = document.querySelectorAll(".room-item");

if (searchInput) {
  searchInput.addEventListener("keyup", function () {
    const keyword = searchInput.value.toLowerCase();

    roomItems.forEach(function (room) {
      const roomName = room.getAttribute("data-name");

      if (roomName.includes(keyword)) {
        room.style.display = "block";
      } else {
        room.style.display = "none";
      }
    });
  });
}

const bookingForm = document.getElementById("bookingForm");
const bookingResult = document.getElementById("bookingResult");

if (bookingForm) {
  bookingForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const guestName = document.getElementById("guestName").value;
    const guestContact = document.getElementById("guestContact").value;
    const roomType = document.getElementById("roomType").value;
    const checkIn = document.getElementById("checkIn").value;
    const checkOut = document.getElementById("checkOut").value;
    const guestCount = document.getElementById("guestCount").value;

    if (!guestName || !guestContact || !roomType || !checkIn || !checkOut || !guestCount) {
      alert("Harap isi semua data booking terlebih dahulu.");
      return;
    }

    if (checkOut <= checkIn) {
      alert("Tanggal check-out harus setelah tanggal check-in.");
      return;
    }

    bookingResult.classList.remove("d-none");
    bookingResult.innerHTML = `
      <strong>Booking berhasil disimulasikan!</strong><br>
      Terima kasih, ${guestName}. Booking untuk ${roomType} dari tanggal ${checkIn} sampai ${checkOut} telah tercatat secara simulasi.
    `;

    bookingForm.reset();
  });
}