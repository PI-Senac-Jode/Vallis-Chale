document.addEventListener('DOMContentLoaded', () => {
  const mainImage = document.getElementById('mainImage');

  if (mainImage) {
    const thumbnails = document.querySelectorAll('.gallery__item:not(.gallery__item--center) img');

    thumbnails.forEach(img => {
      img.addEventListener('click', () => {
        const tempSrc = mainImage.src;
        const tempAlt = mainImage.alt;

        mainImage.src = img.src;
        mainImage.alt = img.alt;

        img.src = tempSrc;
        img.alt = tempAlt;
      });
    });
  }

  document.querySelectorAll('[data-carousel]').forEach(carousel => {
    const track = carousel.querySelector('[data-carousel-track]');
    const prevButton = carousel.querySelector('[data-carousel-prev]');
    const nextButton = carousel.querySelector('[data-carousel-next]');
    const dotsContainer = carousel.parentElement.querySelector('[data-carousel-dots]');

    if (!track || !prevButton || !nextButton) {
      return;
    }

    const cards = Array.from(track.querySelectorAll('.chale-card'));

    if (cards.length === 0) {
      return;
    }

    const getStep = () => {
      const firstCard = cards[0];
      const gap = parseFloat(getComputedStyle(track).columnGap) || 0;

      return firstCard.getBoundingClientRect().width + gap;
    };

    const getMaxIndex = () => Math.max(0, Math.ceil((track.scrollWidth - track.clientWidth) / getStep()));

    const getActiveIndex = () => Math.min(Math.round(track.scrollLeft / getStep()), getMaxIndex());

    const renderDots = () => {
      if (!dotsContainer) {
        return;
      }

      const dotCount = getMaxIndex() + 1;

      if (dotsContainer.children.length === dotCount) {
        return;
      }

      dotsContainer.innerHTML = '';

      Array.from({ length: dotCount }).forEach((_, index) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'hospedagens__dot';
        dot.setAttribute('aria-label', `Ir para grupo ${index + 1} de hospedagens`);
        dot.addEventListener('click', () => {
          track.scrollTo({
            left: getStep() * index,
            behavior: 'smooth'
          });
        });

        dotsContainer.appendChild(dot);
      });
    };

    const updateCarousel = () => {
      renderDots();

      const activeIndex = getActiveIndex();
      const maxScroll = track.scrollWidth - track.clientWidth - 2;

      prevButton.disabled = track.scrollLeft <= 2;
      nextButton.disabled = track.scrollLeft >= maxScroll || maxScroll <= 0;

      if (dotsContainer) {
        dotsContainer.querySelectorAll('.hospedagens__dot').forEach((dot, index) => {
          dot.classList.toggle('is-active', index === activeIndex);
        });
      }
    };

    prevButton.addEventListener('click', () => {
      track.scrollBy({
        left: -getStep(),
        behavior: 'smooth'
      });
    });

    nextButton.addEventListener('click', () => {
      track.scrollBy({
        left: getStep(),
        behavior: 'smooth'
      });
    });

    track.addEventListener('scroll', updateCarousel);
    window.addEventListener('resize', updateCarousel);
    renderDots();
    updateCarousel();
  });
});
