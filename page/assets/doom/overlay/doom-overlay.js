(function () {
  const qs = (s, r = document) => r.querySelector(s);

  document.addEventListener('DOMContentLoaded', () => {
    const root  = qs('#doom-procrastinate');
    const btn   = qs('.doom-open', root);
    const wrap  = qs('#doom-frame-wrap', root);
    const frame = qs('#doom-frame', root);
    const btnFS = qs('.doom-fullscreen', root);
    const btnClose = qs('.doom-close', root);

    let lastFocus = null;

    function focusFrame() {
      const doc = frame.contentDocument;
      doc?.getElementById('doom')?.focus();
      frame.contentWindow?.focus();
    }

    function fixFrame() {
      focusFrame();
    }
    frame.addEventListener('load', fixFrame);

    function open() {
      lastFocus = document.activeElement;
      wrap.hidden = false;
      const url = DOOM_OVERLAY_CFG.engineUrl;
      if (frame.src !== url) frame.src = url;
      frame.focus();
    }

    btn.addEventListener('click', open);

    btnFS.addEventListener('click', () => {
      const fs = frame.contentDocument?.getElementById('fullscreen');
      fs?.click();
      focusFrame();
    });

    btnClose.addEventListener('click', () => {
      wrap.hidden = true;
      frame.src = 'about:blank';
      lastFocus?.focus();
    });
  });
})();
