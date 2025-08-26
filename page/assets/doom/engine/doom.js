(function () {
  console.log('[WebPrBoom] Engine script starting');
  const params = new URLSearchParams(window.location.search);
  const wadUrl = params.get('iwad');
  if (!wadUrl) {
    console.warn('[WebPrBoom] No WAD URL provided');
    return;
  }

  console.log('[WebPrBoom] Fetching WAD', wadUrl);
  fetch(wadUrl)
    .then(async (resp) => {
      console.log(
        '[WebPrBoom] WAD response',
        resp.status,
        resp.statusText
      );
      if (!resp.ok) {
        console.error('[WebPrBoom] Failed to fetch WAD');
        return;
      }
      const buf = await resp.arrayBuffer();
      console.log('[WebPrBoom] WAD loaded', buf.byteLength, 'bytes');
      console.log('[WebPrBoom] Emulator initialization placeholder');
      // TODO: Initialize WebPrBoom emulator with loaded WAD
    })
    .catch((err) => {
      console.error('[WebPrBoom] WAD fetch error', err);
    });
})();
