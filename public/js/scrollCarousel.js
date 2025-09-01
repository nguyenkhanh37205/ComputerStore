// Tìm tất cả carousel
document.querySelectorAll('.content-mt').forEach(container => {
  const track = container.querySelector('.content12');
  const btnLeft = container.querySelector('.carousel-btn-left');
  const btnRight = container.querySelector('.carousel-btn-right');

  const scrollAmount = 300; // số pixel scroll mỗi lần

  // Nút sang trái
  btnLeft.addEventListener('click', () => {
    track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
  });

  // Nút sang phải
  btnRight.addEventListener('click', () => {
    track.scrollBy({ right: scrollAmount, behavior: 'smooth' });
  });
});
