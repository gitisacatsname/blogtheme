'use strict';

(function () {
  var doomCanvas = null;

  window.Module = {
    monitorRunDependencies: function (toLoad) {
      this.dependencies = Math.max(this.dependencies, toLoad);
    },

    inFullscreen: false,
    dependencies: 0,
    
    setStatus: null,
    progress: null,

    loader: null,
    canvas: null
  };

  function getCanvas () {
    doomCanvas.addEventListener('webglcontextlost', function (event) {
      alert('WebGL context lost. You need to reload the page.');
      event.preventDefault();
    }, false);

    doomCanvas.addEventListener('contextmenu', function (event) {
      event.preventDefault();
    });

    return doomCanvas;
  }

  function getStatus (status) {
    var loading = status.match(/([^(]+)\((\d+(\.\d+)?)\/(\d+)\)/);

    if (loading) {
      var progress = loading[2] / loading[4] * 100;
      Module.progress.innerHTML = progress.toFixed(1) + '%';

      if (progress === 100) {
        setTimeout(function () {
          Module.loader.classList.add('completed');
          doomCanvas.classList.add('ready');
        }, 500);

        setTimeout(function () {
          Module.canvas.dispatchEvent(new Event('mousedown'));
        }, 2000);
      }
    }
  }

  function onPointerLockChange () {
    Module.inFullscreen = !Module.inFullscreen;

    if (!Module.inFullscreen) {
      doomCanvas.classList.remove('centered');
    } else {
      doomCanvas.classList.add('centered');
    }
  }

  function onGameClick (game) {
    var doomScript = document.createElement('script');
    document.body.appendChild(doomScript);
    doomScript.type = 'text/javascript';
    doomScript.src = game + '.js';
  }

  window.addEventListener('DOMContentLoaded', function () {
    document.exitPointerLock = document.exitPointerLock || document.mozExitPointerLock || document.webkitExitPointerLock;
    document.exitFullscreen = document.exitFullscreen || document.mozCancelFullScreen || document.webkitCancelFullScreen;

    document.addEventListener('mozpointerlockchange', onPointerLockChange, false);
    document.addEventListener('pointerlockchange', onPointerLockChange, false);

    Module.progress = document.getElementById('progress');
    Module.loader = document.getElementById('loader');
    doomCanvas = document.getElementById('doom');

    Module.setStatus = getStatus;
    Module.canvas = getCanvas();

    var params = new URLSearchParams(window.location.search);
    var autoGame = params.get('game');
    if (autoGame === 'doom1') {
      onGameClick(autoGame);
    } else {
      onGameClick('doom1');
    }
  });
})();
