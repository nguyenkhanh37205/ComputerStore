
document.addEventListener("DOMContentLoaded", function() {
  const slides = document.querySelector(".slides");
  const slideItems = document.querySelectorAll(".slide");
  const dots = document.querySelectorAll(".dot");
  const prevBtn = document.querySelector(".prev");
  const nextBtn = document.querySelector(".next");
  
  let index = 0;
  const total = slideItems.length;
  const intervalTime = 6000; // 6 giây / 1 lần trượt
  let autoSlide;

  // 🌀 Hàm cập nhật slide
  function showSlide(i) {
    index = (i + total) % total; // tránh lỗi âm hoặc vượt
    slides.style.transform = `translateX(-${index * 100}%)`;

    // thêm class active cho ảnh hiện tại
    slideItems.forEach(slide => slide.classList.remove("active"));
    slideItems[index].classList.add("active");

    // cập nhật dot
    dots.forEach(dot => dot.classList.remove("active"));
    dots[index].classList.add("active");
  }

  // Nút điều khiển
  prevBtn.addEventListener("click", () => {
    showSlide(index - 1);
    resetAutoSlide();
  });

  nextBtn.addEventListener("click", () => {
    showSlide(index + 1);
    resetAutoSlide();
  });

  // Dots điều khiển
  dots.forEach(dot => {
    dot.addEventListener("click", (e) => {
      showSlide(parseInt(e.target.dataset.index));
      resetAutoSlide();
    });
  });

  // 🕒 Tự động chuyển slide
  function startAutoSlide() {
    autoSlide = setInterval(() => {
      showSlide(index + 1);
    }, intervalTime);
  }

  function resetAutoSlide() {
    clearInterval(autoSlide);
    startAutoSlide();
  }

  // Bắt đầu
  showSlide(0);
  startAutoSlide();
});
