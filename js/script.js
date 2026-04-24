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