(() => {
  document.body.classList.add('survey-only');
  const steps = Array.from(document.querySelectorAll('.survey-step'));
  if (!steps.length) return;

  const progressWrap = document.querySelector('.survey-progress');
  const progressText = document.getElementById('surveyProgressText');
  const stepButtons = Array.from(document.querySelectorAll('.stepper-step'));
  const closeBtn = document.getElementById('surveyClose');
  const goalsWrap = document.getElementById('surveyGoals');
  const deckSection = document.getElementById('surveyDeck');
  const deckSlidesWrap = document.getElementById('deckSlides');
  const deckSlides = deckSlidesWrap ? Array.from(deckSlidesWrap.querySelectorAll('.deck-slide')) : [];
  const deckProgressBar = document.getElementById('deckProgressBar');
  const deckProgressText = document.getElementById('deckProgressText');
  const deckLaunchBtn = document.getElementById('deckLaunchBtn');
  const deckNextBtn = document.getElementById('deckNextBtn');
  const rankName = document.getElementById('surveyRankName');
  const rankList = document.getElementById('surveyRankList');
  const rankNumTop = document.getElementById('surveyRankNumTop');
  const rankNumMid = document.getElementById('surveyRankNumMid');
  const rankNumYou = document.getElementById('surveyRankNumYou');

  const baseUrl = window.JCP_CONFIG && window.JCP_CONFIG.baseUrl
    ? window.JCP_CONFIG.baseUrl
    : window.location.origin;

  let currentIndex = 0;
  let deckIndex = 0;
  let rankTimers = [];

  const getValue = (id) => (document.getElementById(id)?.value || '').trim();

  const updateProgress = () => {
    if (progressText) {
      progressText.textContent = `Step ${currentIndex + 1} of 3`;
    }
    stepButtons.forEach((btn, idx) => {
      btn.classList.toggle('is-active', idx === currentIndex);
    });
  };

  const showStep = (index) => {
    steps.forEach((step, idx) => {
      step.classList.toggle('active', idx === index);
    });
    deckSection?.classList.remove('active');
    progressWrap?.classList.remove('is-hidden');
    currentIndex = index;
    updateProgress();
  };

  const clearRankTimers = () => {
    if (!rankTimers.length) return;
    rankTimers.forEach((id) => clearTimeout(id));
    rankTimers = [];
  };

  const setRankNums = (top, mid, you) => {
    if (!rankNumTop || !rankNumMid || !rankNumYou) return;
    rankNumTop.textContent = String(top);
    rankNumMid.textContent = String(mid);
    rankNumYou.textContent = String(you);
  };

  const resetRankState = () => {
    if (!rankList) return;
    rankList.classList.remove('promote-step-1', 'promote-step-2');
    setRankNums(1, 2, 3);
  };

  const runRankSequence = () => {
    if (!rankList) return;
    resetRankState();
    clearRankTimers();
    rankTimers.push(setTimeout(() => {
      rankList.classList.add('promote-step-1');
      setRankNums(1, 3, 2);
    }, 450));
    rankTimers.push(setTimeout(() => {
      rankList.classList.remove('promote-step-1');
      rankList.classList.add('promote-step-2');
      setRankNums(2, 3, 1);
    }, 1250));
  };

  const setDeckUI = () => {
    if (!deckSlides.length) return;
    deckSlides.forEach((slide, idx) => {
      slide.classList.toggle('is-active', idx === deckIndex);
      slide.classList.toggle('is-prev', idx < deckIndex);
    });
    const total = deckSlides.length;
    const shown = deckIndex + 1;
    if (deckProgressText) deckProgressText.textContent = `${shown} / ${total}`;
    if (deckProgressBar) deckProgressBar.style.width = `${(shown / total) * 100}%`;
    const isLast = deckIndex === total - 1;
    if (deckLaunchBtn) deckLaunchBtn.classList.toggle('is-hidden', !isLast);
    if (deckNextBtn) deckNextBtn.classList.toggle('is-hidden', isLast);

    if (rankList) {
      const isRankSlide = deckIndex === 3;
      clearRankTimers();
      if (isRankSlide) {
        runRankSequence();
      } else {
        resetRankState();
      }
    }
  };

  const showDeck = () => {
    steps.forEach((step) => step.classList.remove('active'));
    deckSection?.classList.add('active');
    progressWrap?.classList.add('is-hidden');
    deckIndex = 0;
    setDeckUI();
  };

  const validateStep1 = () => {
    const businessName = getValue('businessName');
    const niche = getValue('niche');
    if (!businessName || !niche) {
      alert('Please enter your business name and type to continue.');
      return false;
    }
    return true;
  };

  const validateStep2 = () => {
    const checked = goalsWrap ? goalsWrap.querySelectorAll('input[type="checkbox"]:checked') : [];
    if (!checked.length) {
      alert('Please choose at least one demo goal to continue.');
      return false;
    }
    return true;
  };

  const validateStep3 = () => {
    const firstName = getValue('firstName');
    const emailInput = document.getElementById('email');
    const email = getValue('email');
    if (!firstName) {
      alert('Please enter your first name to continue.');
      return false;
    }
    if (!email || !emailInput?.checkValidity()) {
      emailInput?.classList.add('is-error');
      emailInput?.focus();
      return false;
    }
    return true;
  };

  const enforceGoalLimit = () => {
    if (!goalsWrap) return;
    const checked = goalsWrap.querySelectorAll('input[type="checkbox"]:checked');
    const inputs = goalsWrap.querySelectorAll('input[type="checkbox"]');
    inputs.forEach((input) => {
      if (!input.checked) {
        input.disabled = checked.length >= 2;
      }
    });
  };

  const launchDemo = () => {
    const goals = Array.from(goalsWrap?.querySelectorAll('input[type="checkbox"]:checked') || [])
      .map((input) => input.value);

    try {
      localStorage.removeItem('demoReturnState');
      localStorage.removeItem('directoryDemoListing');
    } catch (e) {
      // no-op
    }

    localStorage.setItem('demoUser', JSON.stringify({
      businessName: getValue('businessName'),
      niche: getValue('niche'),
      serviceArea: getValue('serviceArea'),
      goals,
      firstName: getValue('firstName'),
      email: getValue('email'),
    }));

    window.location.href = `${baseUrl}/demo/?mode=run`;
  };

  const hydrateRankName = () => {
    if (!rankName) return;
    let name = 'Your Business';
    try {
      const stored = JSON.parse(localStorage.getItem('demoUser') || 'null');
      if (stored && stored.businessName) {
        name = stored.businessName;
      }
    } catch (e) {
      // no-op
    }
    rankName.textContent = name;
  };

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;
    const action = btn.dataset.action;
    const deckActive = deckSection?.classList.contains('active');

    if (action === 'next' && !deckActive) {
      if (currentIndex === 0 && !validateStep1()) return;
      if (currentIndex === 1 && !validateStep2()) return;
      if (currentIndex < steps.length - 1) {
        showStep(currentIndex + 1);
      }
    }

    if (action === 'launch' && !deckActive) {
      if (!validateStep3()) return;
      if (deckSlides.length) {
        showDeck();
        return;
      }
      launchDemo();
    }

    if ((action === 'deck-next' || (deckActive && action === 'next')) && deckIndex < deckSlides.length - 1) {
      if (deckIndex < deckSlides.length - 1) {
        deckIndex += 1;
        setDeckUI();
      }
    }

    if (action === 'deck-prev' || (deckActive && action === 'prev')) {
      if (deckIndex > 0) {
        deckIndex -= 1;
        setDeckUI();
      }
    }

    if (action === 'deck-launch' || (deckActive && action === 'launch')) {
      launchDemo();
    }
  });

  stepButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      const target = Number(btn.dataset.step);
      if (Number.isNaN(target)) return;
      if (target > currentIndex) {
        if (currentIndex === 0 && !validateStep1()) return;
        if (currentIndex === 1 && !validateStep2()) return;
      }
      showStep(target);
    });
  });

  document.getElementById('email')?.addEventListener('input', (e) => {
    e.target.classList.remove('is-error');
  });

  goalsWrap?.addEventListener('change', enforceGoalLimit);

  const closeSurvey = () => {
    window.location.href = `${baseUrl}/`;
  };

  closeBtn?.addEventListener('click', closeSurvey);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeSurvey();
    }
  });

  enforceGoalLimit();
  hydrateRankName();
  showStep(0);
})();
