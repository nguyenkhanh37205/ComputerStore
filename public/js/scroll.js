document.addEventListener("DOMContentLoaded", function () {

  const content = document.querySelector('.content1');
  const leftBtn = document.querySelector('.scroll-btn.left');
  const rightBtn = document.querySelector('.scroll-btn.right');

  let scrollX = 0;
  const step = 250; // Số px cuộn mỗi lần

  rightBtn.addEventListener('click', () => {
    scrollX -= step;
    content.style.transform = `translateX(${scrollX}px)`;
    checkLimit();
  });

  leftBtn.addEventListener('click', () => {
    scrollX += step;
    content.style.transform = `translateX(${scrollX}px)`;
    checkLimit();
  });

  function checkLimit() {
    const maxScroll = -(content.scrollWidth - content.parentElement.offsetWidth);
    if (scrollX < maxScroll) scrollX = maxScroll;
    if (scrollX > 0) scrollX = 0;
    content.style.transform = `translateX(${scrollX}px)`;
  }

});