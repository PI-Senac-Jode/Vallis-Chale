document.addEventListener('DOMContentLoaded', () => {
  const mainImage = document.getElementById('mainImage');

  // pega todas as imagens MENOS a do centro
  const thumbnails = document.querySelectorAll('.gallery__item:not(.gallery__item--center) img');

  thumbnails.forEach(img => {
    img.addEventListener('click', () => {
      // troca a imagem principal
      const tempSrc = mainImage.src;
      const tempAlt = mainImage.alt;

      mainImage.src = img.src;
      mainImage.alt = img.alt;

      // opcional: manter preview trocando com a que estava no centro
      img.src = tempSrc;
      img.alt = tempAlt;
    });
  });
});