document.addEventListener('DOMContentLoaded', function () {

  /* 1. Scroll reveal untuk elemen-elemen utama */
  var revealSelectors = '.card, section, .table-responsive, h2, h3.fw-bold, .alert';
  var revealEls = document.querySelectorAll(revealSelectors);
  revealEls.forEach(function (el) { el.classList.add('reveal'); });

  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('reveal-visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('reveal-visible'); });
  }

  /* 2. Navbar berubah tampilan saat discroll */
  var navbar = document.querySelector('.navbar.bg-dark');
  if (navbar) {
    window.addEventListener('scroll', function () {
      navbar.classList.toggle('navbar-scrolled', window.scrollY > 30);
    }, { passive: true });
  }

  /* 3. Efek ripple di setiap tombol .btn */
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.btn');
    if (!btn) return;
    var rect = btn.getBoundingClientRect();
    var size = Math.max(rect.width, rect.height);
    var ripple = document.createElement('span');
    ripple.className = 'btn-ripple';
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
    ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
    btn.appendChild(ripple);
    setTimeout(function () { ripple.remove(); }, 650);
  });

  /* 4. Fade-in halus untuk semua gambar */
  document.querySelectorAll('img').forEach(function (img) {
    if (img.complete && img.naturalWidth !== 0) {
      img.classList.add('img-loaded');
      return;
    }
    img.classList.add('img-loading');
    img.addEventListener('load', function () {
      img.classList.remove('img-loading');
      img.classList.add('img-loaded');
    });
  });

  /* 5. Count-up untuk angka Rupiah (Total Tagihan, harga, dsb) */
  var priceSelectors = '.text-primary.fw-bold, .text-danger.fw-bold, .text-success.fw-bold, td.fw-bold, h3.fw-bold, .fs-5';
  document.querySelectorAll(priceSelectors).forEach(function (el) {
    var raw = el.textContent;
    var match = raw.match(/Rp\s*([\d.,]+)/);
    if (!match) return;
    var target = parseInt(match[1].replace(/[.,]/g, ''), 10);
    if (isNaN(target)) return;

    el.classList.add('count-target');
    var prefix = raw.slice(0, match.index) + 'Rp ';
    var suffix = raw.slice(match.index + match[0].length);
    var duration = 900;
    var start = performance.now();

    function tick(now) {
      var progress = Math.min((now - start) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      var current = Math.floor(target * eased);
      el.textContent = prefix + current.toLocaleString('id-ID') + suffix;
      if (progress < 1) requestAnimationFrame(tick);
      else el.textContent = raw;
    }
    requestAnimationFrame(tick);
  });

  /* 6. Tombol scroll-to-top mengambang */
  var scrollBtn = document.createElement('button');
  scrollBtn.className = 'scroll-top-btn';
  scrollBtn.innerHTML = '&uarr;';
  scrollBtn.setAttribute('aria-label', 'Kembali ke atas');
  document.body.appendChild(scrollBtn);
  window.addEventListener('scroll', function () {
    scrollBtn.classList.toggle('show', window.scrollY > 400);
  }, { passive: true });
  scrollBtn.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

});