document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('img').forEach((image) => {
    image.addEventListener('error', () => { image.style.opacity = '0.35'; }, { once: true });
  });
});
