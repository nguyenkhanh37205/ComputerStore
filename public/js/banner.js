document.addEventListener("DOMContentLoaded", function () {
  const banner = document.getElementById("banner-slider");

  const images = [
    "./images/image.png",
    "./images/banner-la-gi.jpg",
    "./images/banner.webp"
  ];

  let index = 0;

  function changeBackground() {
    banner.style.backgroundImage = `url('${images[index]}')`;
    index = (index + 1) % images.length;
  }

  changeBackground(); // ảnh đầu tiên
  setInterval(changeBackground, 4000); // tự chạy sau mỗi 4 giây
});
