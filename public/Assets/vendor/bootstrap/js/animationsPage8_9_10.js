document.addEventListener('DOMContentLoaded', function () {

  /* ---------- 1. HEADER : ombre + compression au scroll ---------- */
  var header = document.querySelector('.site-header');
  if (header) {
    var onHeaderScroll = function () {
      if (window.scrollY > 24) {
        header.classList.add('is-scrolled');
      } else {
        header.classList.remove('is-scrolled');
      }
    };
    window.addEventListener('scroll', onHeaderScroll, { passive: true });
    onHeaderScroll();
  }

  /* ---------- 2. SCROLL REVEAL (fade / slide-up / zoom / left / right) ---------- */
  var revealEls = document.querySelectorAll('[data-reveal]');

  /* Applique la classe de délai (ex: data-reveal-class="delay-2" -> classList "delay-2")
     afin de permettre un effet "stagger" (cartes qui apparaissent l'une après l'autre). */
  revealEls.forEach(function (el) {
    var delayClass = el.getAttribute('data-reveal-class');
    if (delayClass) { el.classList.add(delayClass); }
  });

  if ('IntersectionObserver' in window && revealEls.length) {
    var revealObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.18, rootMargin: '0px 0px -40px 0px' });

    revealEls.forEach(function (el) { revealObserver.observe(el); });
  } else {
    /* Fallback : navigateurs sans IntersectionObserver */
    revealEls.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* ---------- 3. TEXT REVEAL : découpe les lignes en mots ---------- */
  var textRevealLines = document.querySelectorAll('.text-reveal-line');
  textRevealLines.forEach(function (line) {
    var words = line.textContent.trim().split(/\s+/);
    line.innerHTML = words
      .map(function (w, i) {
        return '<span class="word" style="transition-delay:' + (i * 0.045) + 's">' + w + '&nbsp;</span>';
      })
      .join('');
  });

  if ('IntersectionObserver' in window && textRevealLines.length) {
    var textObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          textObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.4 });
    textRevealLines.forEach(function (el) { textObserver.observe(el); });
  } else {
    textRevealLines.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* ---------- 4. PARALLAXE LÉGER (sur scroll, throttled via rAF) ---------- */
  var parallaxEls = document.querySelectorAll('.parallax-layer');
  var ticking = false;

  function updateParallax() {
    var scrollY = window.scrollY;
    parallaxEls.forEach(function (el) {
      var speed = parseFloat(el.getAttribute('data-speed')) || 0.15;
      var rect = el.closest('.parallax-wrap') ? el.closest('.parallax-wrap').getBoundingClientRect() : el.getBoundingClientRect();
      /* Applique seulement si l'élément est visible dans le viewport (perf) */
      if (rect.bottom > 0 && rect.top < window.innerHeight) {
        var offset = (scrollY - (el.dataset.baseOffset || 0)) * speed;
        el.style.transform = 'translate3d(0,' + offset.toFixed(1) + 'px,0)';
      }
    });
    ticking = false;
  }

  function onScrollParallax() {
    if (!ticking) {
      window.requestAnimationFrame(updateParallax);
      ticking = true;
    }
  }

  if (parallaxEls.length && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    window.addEventListener('scroll', onScrollParallax, { passive: true });
    updateParallax();
  }

  /* ---------- 5. SMOOTH ANCHOR LINKS (offset header) ---------- */
  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var targetId = this.getAttribute('href');
      if (targetId.length > 1) {
        var target = document.querySelector(targetId);
        if (target) {
          e.preventDefault();
          var headerHeight = header ? header.offsetHeight : 0;
          var top = target.getBoundingClientRect().top + window.scrollY - headerHeight - 16;
          window.scrollTo({ top: top, behavior: 'smooth' });
        }
      }
    });
  });

  /* ---------- 6. ACCORDÉON FAQ (page À propos) — chevron rotation ---------- */
  document.querySelectorAll('.faq-trigger').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var expanded = this.getAttribute('aria-expanded') === 'true';
      this.classList.toggle('is-open', !expanded);
    });
  });

});
