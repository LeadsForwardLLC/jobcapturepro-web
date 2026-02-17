/**
 * WP Plugin Prototype – main slider (3 check-ins at a time) and per-card image carousels.
 */

(function () {
  var sliderTrack = document.getElementById('jcp-plugin-slider-track');
  var sliderViewport = sliderTrack ? sliderTrack.parentElement : null;
  var sliderPrev = document.getElementById('jcp-plugin-slider-prev');
  var sliderNext = document.getElementById('jcp-plugin-slider-next');

  var gap = 24;
  var currentIndex = 0;
  var totalCards = 0;

  function getCardsVisible() {
    if (!sliderViewport) return 3;
    var vw = sliderViewport.offsetWidth;
    if (vw < 640) return 1;
    if (vw < 1024) return 2;
    return 3;
  }
  var cardsVisible = 3;

  function getViewportWidth(el) {
    return el && el.offsetWidth ? el.offsetWidth : 0;
  }

  function setSliderVars() {
    if (!sliderViewport || !sliderTrack) return;
    cardsVisible = getCardsVisible();
    var vw = getViewportWidth(sliderViewport);
    if (vw <= 0) return;
    var cardWidth = (vw - (cardsVisible - 1) * gap) / cardsVisible;
    var step = cardWidth + gap;
    sliderTrack.style.setProperty('--card-width', cardWidth + 'px');
    sliderTrack.style.setProperty('--card-step', step + 'px');
    totalCards = sliderTrack.querySelectorAll('.jcp-plugin-card').length;
    updateSliderPosition();
    updateSliderButtons();
  }

  function updateSliderPosition() {
    if (!sliderTrack) return;
    var step = parseFloat(sliderTrack.style.getPropertyValue('--card-step')) || 324;
    sliderTrack.style.transform = 'translateX(-' + currentIndex * step + 'px)';
  }

  function updateSliderButtons() {
    if (sliderPrev) sliderPrev.disabled = currentIndex <= 0;
    if (sliderNext) sliderNext.disabled = totalCards <= cardsVisible || currentIndex >= totalCards - cardsVisible;
  }

  function goPrev() {
    if (currentIndex <= 0) return;
    currentIndex--;
    updateSliderPosition();
    updateSliderButtons();
  }

  function goNext() {
    if (totalCards <= cardsVisible || currentIndex >= totalCards - cardsVisible) return;
    currentIndex++;
    updateSliderPosition();
    updateSliderButtons();
  }

  if (sliderPrev) sliderPrev.addEventListener('click', goPrev);
  if (sliderNext) sliderNext.addEventListener('click', goNext);

  var resizeTimeout;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function () {
      if (!sliderViewport) return;
      var vw = getViewportWidth(sliderViewport);
      if (vw <= 0) return;
      cardsVisible = getCardsVisible();
      setSliderVars();
    }, 100);
  });

  // Per-card image carousels
  document.querySelectorAll('.jcp-plugin-card__gallery[data-carousel]').forEach(function (gallery) {
    var slides = gallery.querySelectorAll('.jcp-plugin-card__slide');
    var prevBtn = gallery.querySelector('.jcp-plugin-card__nav--prev');
    var nextBtn = gallery.querySelector('.jcp-plugin-card__nav--next');
    var dots = gallery.querySelectorAll('.jcp-plugin-card__dot');
    var total = slides.length;
    var active = 0;

    function setActive(i) {
      if (i < 0) i = total - 1;
      if (i >= total) i = 0;
      active = i;
      slides.forEach(function (s, idx) {
        s.classList.toggle('is-active', idx === active);
      });
      dots.forEach(function (d, idx) {
        d.classList.toggle('is-active', idx === active);
      });
    }

    if (prevBtn) prevBtn.addEventListener('click', function () { setActive(active - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { setActive(active + 1); });
    dots.forEach(function (d, idx) {
      d.addEventListener('click', function () { setActive(idx); });
    });
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      setSliderVars();
    });
  } else {
    setSliderVars();
  }
})();
