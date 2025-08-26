<?php
/**
 * Download and extract the original DOOM shareware WAD.
 *
 * This script downloads the Debian source package for the shareware WAD and
 * copies doom1.wad into the theme's assets directory. It keeps the license
 * information alongside the WAD.
 */

if (!defined('NC_SHAREWARE_URL')) {
    define('NC_SHAREWARE_URL', 'https://github.com/Akbar30Bill/DOOM_wads/raw/refs/heads/master/doom1.wad');
}

/**
 * Download the shareware WAD to the given directory.
 *
 * @param string $destDir Destination directory for doom1.wad.
 * @param string $url     Optional URL of the tarball.
 * @return void
 */
function nc_download_shareware_wad($destDir, $url = NC_SHAREWARE_URL) {
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $tmpFile = tempnam(sys_get_temp_dir(), 'doom');
    if (!copy($url, $tmpFile)) {
        throw new RuntimeException('Failed to download shareware WAD');
    }

    $path = parse_url($url, PHP_URL_PATH);
    if (preg_match('/\.tar\.gz$/i', $path)) {
        $tarGz = $tmpFile . '.tar.gz';
        rename($tmpFile, $tarGz);

        $extractDir = sys_get_temp_dir() . '/doom_shareware_' . uniqid();
        mkdir($extractDir);
        shell_exec('tar -xzf ' . escapeshellarg($tarGz) . ' -C ' . escapeshellarg($extractDir));

        $wadSource = $extractDir . '/doom1.wad';
        if (!file_exists($wadSource)) {
            throw new RuntimeException('doom1.wad not found in archive');
        }
        copy($wadSource, $destDir . '/doom1.wad');

        $license = $extractDir . '/debian/copyright';
        if (file_exists($license)) {
            copy($license, $destDir . '/doom1.copyright');
        }

        // Cleanup
        unlink($tarGz);
        if (file_exists($wadSource)) {
            unlink($wadSource);
        }
        if (file_exists($extractDir . '/debian/copyright')) {
            unlink($extractDir . '/debian/copyright');
            rmdir($extractDir . '/debian');
        }
        if (is_dir($extractDir)) {
            rmdir($extractDir);
        }
    } else {
        copy($tmpFile, $destDir . '/doom1.wad');
        unlink($tmpFile);
    }
}

if (PHP_SAPI === 'cli' && isset($argv) && realpath($argv[0]) === __FILE__) {
    $dest = dirname(__DIR__) . '/page/assets/doom/iwads';
    nc_download_shareware_wad($dest);
    echo "Downloaded shareware WAD to $dest\n";
}
