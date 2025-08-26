(function () {
  const qs = (s, r = document) => r.querySelector(s);

  function setIWADParam(url, iwadUrl) {
    try {
      const u = new URL(url, window.location.origin);
      if (iwadUrl) u.searchParams.set('iwad', iwadUrl);
      return u.toString();
    } catch {
      return url;
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    console.log('[DoomOverlay] DOMContentLoaded');
    const root  = qs('#doom-procrastinate');
    const btn   = qs('.doom-open', root);
    const wrap  = qs('#doom-frame-wrap', root);
    const frame = qs('#doom-frame', root);
    const btnFS = qs('.doom-fullscreen', root);
    const btnClose = qs('.doom-close', root);
    const btnFreedoom = qs('.doom-iwad-freedoom', root);
    const btnShare = qs('.doom-iwad-shareware', root);

    [DOOM_OVERLAY_CFG.freedoomUrl, DOOM_OVERLAY_CFG.sharewareUrl].forEach(u => {
      if (!u) return;
      console.log('[DoomOverlay] Prefetching WAD', u);
      fetch(u, { mode: 'no-cors' })
        .then(res => console.log('[DoomOverlay] Prefetch response type', res.type))
        .catch(err => console.error('[DoomOverlay] Prefetch failed', err));
    });

    function open(iwadUrl) {
      console.log('[DoomOverlay] Opening overlay with', iwadUrl);
      wrap.hidden = false;
      const url = setIWADParam(DOOM_OVERLAY_CFG.engineUrl, iwadUrl || null);
      if (frame.src !== url) frame.src = url;
    }

    frame.addEventListener('load', () => {
      console.log('[DoomOverlay] Frame loaded', frame.src);
    });

    btn.addEventListener('click', () => {
      console.log('[DoomOverlay] Procrastinate button clicked');
      open(DOOM_OVERLAY_CFG.freedoomUrl);
    });

    btnFreedoom.addEventListener('click', () => {
      console.log('[DoomOverlay] Freedoom WAD selected');
      frame.src = setIWADParam(
        DOOM_OVERLAY_CFG.engineUrl,
        DOOM_OVERLAY_CFG.freedoomUrl
      );
    });

    btnShare?.addEventListener('click', () => {
      console.log('[DoomOverlay] Shareware WAD selected');
      frame.src = setIWADParam(
        DOOM_OVERLAY_CFG.engineUrl,
        DOOM_OVERLAY_CFG.sharewareUrl
      );
    });

    btnFS.addEventListener('click', async () => {
      console.log('[DoomOverlay] Fullscreen toggled');
      wrap.classList.toggle('full');
      if (frame.requestFullscreen) frame.requestFullscreen().catch(() => {});
    });

    btnClose.addEventListener('click', () => {
      console.log('[DoomOverlay] Overlay closed');
      wrap.hidden = true;
      frame.src = 'about:blank';
    });
  });
})();
