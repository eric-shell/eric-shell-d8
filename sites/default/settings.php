<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all envrionments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to insure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

/**
 * Install profile configuration
 */
$settings['install_profile'] = 'standard';

/**
 * Trusted host domains that can access the information of this site
 */
$settings['trusted_host_patterns'] = array(
  '^es$',
  '^eric\.sh$',
  '^www\.eric\.sh$',
  '^dev-eric-shell\.pantheon\.io$',
  '^dev-eric-shell\.pantheonsite\.io$',
  '^test-eric-shell\.pantheon\.io$',
  '^test-eric-shell\.pantheonsite\.io$',
  '^live-eric-shell\.pantheon\.io$',
  '^live-eric-shell\.pantheonsite\.io$',
);

/**
 * Page caching:
 *
 * By default, Drupal sends a "Vary: Cookie" HTTP header for anonymous page
 * views. This tells a HTTP proxy that it may return a page from its local
 * cache without contacting the web server, if the user sends the same Cookie
 * header as the user who originally requested the cached page. Without "Vary:
 * Cookie", authenticated users would also be served the anonymous page from
 * the cache. If the site has mostly anonymous users except a few known
 * editors/administrators, the Vary header can be omitted. This allows for
 * better caching in HTTP proxies (including reverse proxies), i.e. even if
 * clients send different cookies, they still get content served from the cache.
 * However, authenticated users should access the site directly (i.e. not use an
 * HTTP proxy, and bypass the reverse proxy if one is used) in order to avoid
 * getting cached pages from the proxy.
 */
$settings['omit_vary_cookie'] = TRUE;

// Redirect all traffic to non-www. For example yoursite.com
if (isset($_SERVER['PANTHEON_ENVIRONMENT']) &&
  ($_SERVER['PANTHEON_ENVIRONMENT'] === 'live') &&
  (php_sapi_name() != "cli")) {
  if ($_SERVER['HTTP_HOST'] == 'www.eric.sh') {
    header('HTTP/1.0 301 Moved Permanently');
    header('Location: http://eric.sh'. $_SERVER['REQUEST_URI']);
    exit();
  }
}
