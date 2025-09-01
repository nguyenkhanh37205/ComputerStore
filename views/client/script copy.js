const bg = document.querySelector('.background-slide');

const backgrounds = [
  'url("./../../public/images/image.png)',
  'url("banner2.jpg")',
  'url("banner3.jpg")'
];

let current = 0;

function changeBackground() {
  bg.style.backgroundImage = backgrounds[current];
  current = (current + 1) % backgrounds.length;
}

changeBackground(); // hiển thị ảnh đầu tiên
setInterval(changeBackground, 4000); // đổi ảnh mỗi 4 giây
