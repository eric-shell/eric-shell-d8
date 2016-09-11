<?php

namespace Drupal\simple_sitemap;

use \XMLWriter;

/**
 * SitemapGenerator class.
 */
class SitemapGenerator {

  const XML_VERSION = '1.0';
  const ENCODING = 'UTF-8';
  const XMLNS = 'http://www.sitemaps.org/schemas/sitemap/0.9';
  const XMLNS_XHTML = 'http://www.w3.org/1999/xhtml';
  const GENERATED_BY = 'Generated by the Simple XML sitemap Drupal module: https://drupal.org/project/simple_sitemap.';

  private $generator;
  private $db;
  private $moduleHandler;
  private $defaultLanguageId;
  private $generateFrom = 'form';

  function __construct($generator, $database, $language_manager, $module_handler) {
    $this->generator = $generator;
    $this->db = $database;
    $this->defaultLanguageId = $language_manager->getDefaultLanguage()->getId();
    $this->moduleHandler = $module_handler;
  }

  public function setGenerateFrom($from) {
    $this->generateFrom = $from;
    return $this;
  }

  /**
   * Adds all operations to the batch and starts it.
   */
  public function startGeneration() {
    $batch = new Batch();
    $batch->setBatchInfo([
      'from' => $this->generateFrom,
      'batch_process_limit' => !empty($this->generator->getSetting('batch_process_limit'))
        ? $this->generator->getSetting('batch_process_limit') : NULL,
      'max_links' => $this->generator->getSetting('max_links', 2000),
      'skip_untranslated' => $this->generator->getSetting('skip_untranslated', FALSE),
      'remove_duplicates' => $this->generator->getSetting('remove_duplicates', TRUE),
      'entity_types' => $this->generator->getConfig('entity_types'),
    ]);
    // Add custom link generating operation.
    $batch->addOperation('generateCustomUrls', $this->getCustomUrlsData());

    // Add entity link generating operations.
    foreach($this->getEntityTypeData() as $data) {
      $batch->addOperation('generateBundleUrls', $data);
    }
    $batch->start();
  }

  /**
   * Returns a batch-ready data array for custom link generation.
   *
   * @return array
   *  Data to be processed.
   */
  private function getCustomUrlsData() {
    $paths = [];
    foreach($this->generator->getConfig('custom') as $i => $custom_path) {
      $paths[$i]['path'] = $custom_path['path'];
      $paths[$i]['priority'] = isset($custom_path['priority']) ? $custom_path['priority'] : NULL;
      $paths[$i]['lastmod'] = NULL; //todo: implement lastmod
    }
    return $paths;
  }

  /**
   * Collects entity metadata for entities that are set to be indexed
   * and returns an array of batch-ready data sets for entity link generation.
   *
   * @return array
   */
  private function getEntityTypeData() {
    $data_sets = [];
    $sitemap_entity_types = $this->generator->getSitemapEntityTypes();
    $entity_types = $this->generator->getConfig('entity_types');
    foreach($entity_types as $entity_type_name => $bundles) {
      if (isset($sitemap_entity_types[$entity_type_name])) {
        $keys = $sitemap_entity_types[$entity_type_name]->getKeys();
        $keys['bundle'] = $entity_type_name == 'menu_link_content' ? 'menu_name' : $keys['bundle']; // Menu fix.
        foreach($bundles as $bundle_name => $bundle_settings) {
          if ($bundle_settings['index']) {
            $data_sets[] = [
              'bundle_settings' => $bundle_settings,
              'bundle_name' => $bundle_name,
              'entity_type_name' => $entity_type_name,
              'keys' => $keys,
            ];
          }
        }
      }
    }
    return $data_sets;
  }

  /**
   * Wrapper method which takes links along with their options, lets other
   * modules alter the links and then generates and saves the sitemap.
   *
   * @param array $links
   *  All links with their multilingual versions and settings.
   * @param bool $remove_sitemap
   *  Remove old sitemap from database before inserting the new one.
   */
  public function generateSitemap($links, $remove_sitemap = FALSE) {
    // Invoke alter hook.
    $this->moduleHandler->alter('simple_sitemap_links', $links);
    $values = [
      'id' => $remove_sitemap ? 1 : $this->db->query('SELECT MAX(id) FROM {simple_sitemap}')->fetchField() + 1,
      'sitemap_string' => $this->generateSitemapChunk($links),
      'sitemap_created' => REQUEST_TIME,
    ];
    if ($remove_sitemap) {
      $this->db->truncate('simple_sitemap')->execute();
    }
    $this->db->insert('simple_sitemap')->fields($values)->execute();
  }

  /**
   * Generates and returns the sitemap index for all sitemap chunks.
   *
   * @param array $chunks
   *  All sitemap chunks keyed by the chunk ID.
   *
   * @return string sitemap index
   */
  public function generateSitemapIndex($chunks) {
    $writer = new XMLWriter();
    $writer->openMemory();
    $writer->setIndent(TRUE);
    $writer->startDocument(self::XML_VERSION, self::ENCODING);
    $writer->writeComment(self::GENERATED_BY);
    $writer->startElement('sitemapindex');
    $writer->writeAttribute('xmlns', self::XMLNS);

    foreach ($chunks as $chunk_id => $chunk_data) {
      $writer->startElement('sitemap');
      $writer->writeElement('loc', $GLOBALS['base_url'] . '/sitemaps/'
        . $chunk_id . '/' . 'sitemap.xml');
      $writer->writeElement('lastmod', date_iso8601($chunk_data->sitemap_created));
      $writer->endElement();
    }
    $writer->endElement();
    $writer->endDocument();
    return $writer->outputMemory();
  }

  /**
   * Generates and returns a sitemap chunk.
   *
   * @param array $links
   *  All links with their multilingual versions and settings.
   *
   * @return string
   *  Sitemap chunk
   */
  private function generateSitemapChunk($links) {
    $writer = new XMLWriter();
    $writer->openMemory();
    $writer->setIndent(TRUE);
    $writer->startDocument(self::XML_VERSION, self::ENCODING);
    $writer->writeComment(self::GENERATED_BY);
    $writer->startElement('urlset');
    $writer->writeAttribute('xmlns', self::XMLNS);
    $writer->writeAttribute('xmlns:xhtml', self::XMLNS_XHTML);

    foreach ($links as $link) {

      // Add each translation variant URL to the sitemap.
      $writer->startElement('url');
      $writer->writeElement('loc', $link['url']);

      // Add all alternate URLs to this translation variant.
      foreach($link['alternate_urls'] as $language_id => $alternate_url) {
        $writer->startElement('xhtml:link');
        $writer->writeAttribute('rel', 'alternate');
        $writer->writeAttribute('hreflang', $language_id);
        $writer->writeAttribute('href', $alternate_url);
        $writer->endElement();
      }
      if (isset($link['priority'])) { // Add priority if any.
        $writer->writeElement('priority', $link['priority']);
      }
      if (isset($link['lastmod'])) { // Add lastmod if any.
        $writer->writeElement('lastmod', $link['lastmod']);
      }
      $writer->endElement();
    }
    $writer->endElement();
    $writer->endDocument();
    return $writer->outputMemory();
  }
}

