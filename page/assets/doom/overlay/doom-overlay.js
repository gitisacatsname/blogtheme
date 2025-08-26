(function () {
  const qs = (s, r = document) => r.querySelector(s);

  function setGameParam(url, game) {
    try {
      const u = new URL(url, window.location.origin);
      if (game) u.searchParams.set('game', game);
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

    function open(game) {
      wrap.hidden = false;
      const url = setGameParam(DOOM_OVERLAY_CFG.engineUrl, game || null);
      if (frame.src !== url) frame.src = url;
    }

    btn.addEventListener('click', () => open(DOOM_OVERLAY_CFG.gameFreedoom));

    btnFreedoom.addEventListener('click', () => {
      frame.src = setGameParam(DOOM_OVERLAY_CFG.engineUrl, DOOM_OVERLAY_CFG.gameFreedoom);
    });

    btnShare?.addEventListener('click', () => {
      frame.src = setGameParam(DOOM_OVERLAY_CFG.engineUrl, DOOM_OVERLAY_CFG.gameShareware);
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
