const slider = document.querySelector('.background-slider');
let currentIndex = 0;

function slideBackground() {
  currentIndex = (currentIndex + 1) % 3;
  const offset = -currentIndex * 100;
  slider.style.transform = `translateX(${offset}%)`;
}

setInterval(slideBackground, 4000);
