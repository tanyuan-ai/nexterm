document.addEventListener('DOMContentLoaded', function() {
  // Mobile menu toggle
  var m = document.querySelector('.menu'), s = document.querySelector('.sidebar');
  if (m) m.addEventListener('click', function() {
    var open = s.classList.toggle('open');
    m.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  // LED status (legacy element; kept for back-compat, no-op when absent)
  var status = document.querySelector('.led-status');
  window.addEventListener('load', function() { if (status) status.classList.add('ready') });
  if (document.readyState === 'complete' && status) status.classList.add('ready');

  // Post filtering and search
  var filters = document.querySelectorAll('.filter'),
      links = document.querySelectorAll('[data-category-link]'),
      rows = document.querySelectorAll('.post-row'),
      input = document.getElementById('journal-search'),
      none = document.getElementById('no-results'),
      shell = document.getElementById('search-shell'),
      active = 'all';

  function render() {
    var term = input ? input.value.trim().toLowerCase() : '';
    if (shell) shell.classList.toggle('has-value', !!term);
    var count = 0;
    rows.forEach(function(row) {
      var cat = row.dataset.category || '',
          text = (row.dataset.search || '').toLowerCase(),
          show = (active === 'all' || cat === active) && (!term || text.indexOf(term) > -1);
      row.classList.toggle('hide', !show);
      if (show) count++;
    });
    if (none) none.classList.toggle('show', rows.length > 0 && count === 0);
  }

  function pick(cat) {
    active = cat;
    filters.forEach(function(x) { x.classList.toggle('active', x.dataset.filter === cat) });
    links.forEach(function(x) { x.classList.toggle('active', x.dataset.categoryLink === cat) });
    render();
  }

  filters.forEach(function(b) {
    b.addEventListener('click', function() { pick(b.dataset.filter) });
  });

  links.forEach(function(a) {
    a.addEventListener('click', function(e) {
      if (location.pathname === a.pathname && location.hash.indexOf('#projects') === 0) {
        e.preventDefault();
        pick(a.dataset.categoryLink);
        document.getElementById('projects').scrollIntoView({ behavior: 'smooth' });
      }
    });
  });

  if (input) {
    input.addEventListener('input', render);
    document.addEventListener('keydown', function(e) {
      if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        input.focus();
      }
    });
  }

  // Footer clock (right slot, customizable tz + template with -- placeholder)
  function footerClock() {
    var x = document.getElementById('footer-clock');
    if (!x) return;
    var tpl = x.getAttribute('data-template') || '--';
    var tz = x.getAttribute('data-tz') || 'Asia/Shanghai';
    var p = new Intl.DateTimeFormat('en-GB', {
      timeZone: tz, hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
    }).formatToParts(new Date()).reduce(function(o, a) { o[a.type] = a.value; return o; }, {});
    var timeStr = p.hour + ':' + p.minute + ':' + p.second;
    x.textContent = tpl.indexOf('--') > -1 ? tpl.replace('--', timeStr) : tpl + ' ' + timeStr;
  }
  footerClock();
  setInterval(footerClock, 1000);

  // Menu item terminal-style effects
  var menuLinks = document.querySelectorAll('.topbar-link');
  menuLinks.forEach(function(link) {
    link.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-2px)';
      this.style.boxShadow = '0 4px 8px rgba(124, 131, 255, 0.2)';
    });
    link.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = 'none';
    });
  });

  // ---- Shared text effects: scramble + typewriter ----
  var FX_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*<>/\\|=+';
  function fxScramble(el, target, opts) {
    opts = opts || {};
    var frame = 0, perChar = opts.perChar || 1.4, frameMs = opts.frameMs || 35;
    function tick() {
      var out = '';
      for (var i = 0; i < target.length; i++) {
        if (frame >= 4 + i * perChar) out += target[i];
        else if (target[i] === ' ') out += ' ';
        else out += FX_CHARS[Math.floor(Math.random() * FX_CHARS.length)];
      }
      el.textContent = out;
      frame++;
      if (frame <= 4 + target.length * perChar) setTimeout(tick, frameMs);
      else if (opts.done) opts.done();
    }
    tick();
  }
  function fxTypewriter(el, target, opts) {
    opts = opts || {};
    var i = 0, charMs = opts.charMs || 70;
    (function tick() {
      el.textContent = target.slice(0, ++i);
      if (i < target.length) setTimeout(tick, charMs);
      else if (opts.done) opts.done();
    })();
  }
  function fxApply(el, target, fx, done) {
    if (fx === 'scramble') fxScramble(el, target, { done: done });
    else if (fx === 'typewriter') fxTypewriter(el, target, { done: done });
    else { el.textContent = target; if (done) done(); }
  }

  // ---- 2. Topbar LED status text (custom + fx) ----
  var ledText = document.getElementById('topbar-ledtext');
  if (ledText) {
    var ledFx = ledText.dataset.ledtextFx || 'scramble';
    fxApply(ledText, ledText.dataset.ledtext || 'SYSTEM READY', ledFx);
  }

  // ---- 6. CRT screen: face or custom text ----
  var screenEl2 = document.querySelector('[data-crt-screen]');
  if (screenEl2) {
    var faceEl2 = screenEl2.querySelector('[data-crt-face]');
    var outEl = screenEl2.querySelector('[data-crt-textout]');
    var mode = screenEl2.dataset.crtContent || 'face';
    if (mode === 'text' && faceEl2) faceEl2.classList.add('is-hidden');
    if (mode === 'text' && outEl) {
      var crtFx = screenEl2.dataset.crtTextFx || 'typewriter';
      var crtTarget = screenEl2.dataset.crtText || '';
      // after any fx finishes, check overflow -> switch to infinite marquee scroll
      var maybeScroll = function() {
        outEl.classList.add('is-done');
        // let layout settle (fx may still be mid-transition)
        setTimeout(function() {
          var overV = outEl.scrollHeight > outEl.clientHeight + 2;
          var overH = outEl.scrollWidth > outEl.clientWidth + 2;
          if (overV || overH) {
            outEl.classList.add('is-marquee');
            if (overV && !overH) outEl.classList.add('is-v');
            outEl.classList.remove('is-typing');
            // duplicate content for a seamless loop
            outEl.setAttribute('data-scrolltext', crtTarget);
            outEl.textContent = '';
            var seq = document.createElement('span');
            seq.className = 'crt-marquee-seq';
            seq.textContent = crtTarget;
            outEl.appendChild(seq);
            var seq2 = seq.cloneNode(true);
            outEl.appendChild(seq2);
            // duration proportional to content length
            var dur = Math.max(8, Math.round(crtTarget.length / 4));
            outEl.style.setProperty('--crt-marquee-dur', dur + 's');
          }
        }, 60);
      };
      if (crtFx === 'typewriter') {
        outEl.classList.add('is-typing');
        var caret = document.createElement('span');
        caret.className = 'crt-caret';
        outEl.appendChild(caret);
        // type into a text node before the caret
        var tn = document.createTextNode('');
        outEl.insertBefore(tn, caret);
        var ci = 0;
        (function typeCrt() {
          tn.textContent = crtTarget.slice(0, ++ci);
          if (ci < crtTarget.length) setTimeout(typeCrt, 80);
          else { outEl.classList.remove('is-typing'); maybeScroll(); }
        })();
      } else {
        fxApply(outEl, crtTarget, crtFx, maybeScroll);
      }
    }
  }
});