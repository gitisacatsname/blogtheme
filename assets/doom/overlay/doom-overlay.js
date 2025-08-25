(function () {
  const qs = (s, r = document) => r.querySelector(s);

  function setIWADParam(url, iwadUrl) {
    try {
      const u = new URL(url, window.location.origin);
      if (iwadUrl) u.searchParams.set('iwad', iwadUrl);
      return u.toString();
    } catch { return url; }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const root  = qs('#doom-procrastinate');
    const btn   = qs('.doom-open', root);
    const wrap  = qs('#doom-frame-wrap', root);
    const frame = qs('#doom-frame', root);
    const btnFS = qs('.doom-fullscreen', root);
    const btnClose = qs('.doom-close', root);
    const btnFreedoom = qs('.doom-iwad-freedoom', root);
    const btnShare = qs('.doom-iwad-shareware', root);

    function open(iwadUrl) {
      wrap.hidden = false;
      const url = setIWADParam(DOOM_OVERLAY_CFG.engineUrl, iwadUrl || null);
      if (frame.src !== url) frame.src = url;
    }

    btn.addEventListener('click', () => open(DOOM_OVERLAY_CFG.freedoomUrl));

    btnFreedoom.addEventListener('click', () => {
      frame.src = setIWADParam(DOOM_OVERLAY_CFG.engineUrl, DOOM_OVERLAY_CFG.freedoomUrl);
    });

    btnShare?.addEventListener('click', () => {
      if (!DOOM_OVERLAY_CFG.sharewareUrl) {
        alert('Shareware WAD not bundled. Ask the admin to add doom1.wad or enable auto-download.');
        return;
      }
      frame.src = setIWADParam(DOOM_OVERLAY_CFG.engineUrl, DOOM_OVERLAY_CFG.sharewareUrl);
    });

    btnFS.addEventListener('click', async () => {
      wrap.classList.toggle('full');
      if (frame.requestFullscreen) frame.requestFullscreen().catch(() => {});
    });

    btnClose.addEventListener('click', () => {
      wrap.hidden = true;
      frame.src = 'about:blank';
    });
  });
})();
