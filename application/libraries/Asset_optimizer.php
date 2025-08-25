<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Asset Optimizer Library
 * Provides CSS and JavaScript minification and optimization
 */
class Asset_optimizer {

    protected $CI;
    protected $css_files = array();
    protected $js_files = array();
    protected $minified_path = 'assets/minified/';
    protected $cache_buster = '';

    public function __construct() {
        $this->CI =& get_instance();
        $this->cache_buster = filemtime(FCPATH . 'index.php'); // Use index.php modification time as cache buster
        
        // Create minified directory if it doesn't exist
        if (!is_dir(FCPATH . $this->minified_path)) {
            mkdir(FCPATH . $this->minified_path, 0755, true);
        }
    }

    /**
     * Add CSS file to optimization queue
     * @param string $file CSS file path
     * @param string $media Media type (optional)
     */
    public function add_css($file, $media = 'all') {
        $this->css_files[] = array(
            'file' => $file,
            'media' => $media
        );
    }

    /**
     * Add JavaScript file to optimization queue
     * @param string $file JavaScript file path
     * @param bool $defer Whether to defer loading
     */
    public function add_js($file, $defer = false) {
        $this->js_files[] = array(
            'file' => $file,
            'defer' => $defer
        );
    }

    /**
     * Generate optimized CSS output
     * @param bool $minify Whether to minify the CSS
     * @return string CSS HTML tags
     */
    public function render_css($minify = true) {
        if (empty($this->css_files)) {
            return '';
        }

        if ($minify && ENVIRONMENT === 'production') {
            return $this->render_minified_css();
        } else {
            return $this->render_individual_css();
        }
    }

    /**
     * Generate optimized JavaScript output
     * @param bool $minify Whether to minify the JavaScript
     * @return string JavaScript HTML tags
     */
    public function render_js($minify = true) {
        if (empty($this->js_files)) {
            return '';
        }

        if ($minify && ENVIRONMENT === 'production') {
            return $this->render_minified_js();
        } else {
            return $this->render_individual_js();
        }
    }

    /**
     * Render individual CSS files (development mode)
     * @return string CSS HTML tags
     */
    private function render_individual_css() {
        $output = '';
        foreach ($this->css_files as $css) {
            $file_path = $css['file'];
            if (!preg_match('/^https?:\/\//', $file_path)) {
                $file_path = base_url($file_path) . '?v=' . $this->cache_buster;
            }
            $output .= '<link rel="stylesheet" type="text/css" href="' . $file_path . '" media="' . $css['media'] . '">' . "\n";
        }
        return $output;
    }

    /**
     * Render individual JavaScript files (development mode)
     * @return string JavaScript HTML tags
     */
    private function render_individual_js() {
        $output = '';
        foreach ($this->js_files as $js) {
            $file_path = $js['file'];
            if (!preg_match('/^https?:\/\//', $file_path)) {
                $file_path = base_url($file_path) . '?v=' . $this->cache_buster;
            }
            $defer = $js['defer'] ? ' defer' : '';
            $output .= '<script src="' . $file_path . '"' . $defer . '></script>' . "\n";
        }
        return $output;
    }

    /**
     * Render minified CSS (production mode)
     * @return string Minified CSS HTML tag
     */
    private function render_minified_css() {
        $css_hash = $this->generate_file_hash($this->css_files, 'css');
        $minified_file = $this->minified_path . 'app-' . $css_hash . '.min.css';
        $minified_path = FCPATH . $minified_file;

        if (!file_exists($minified_path) || $this->should_regenerate($minified_path)) {
            $this->create_minified_css($minified_path);
        }

        return '<link rel="stylesheet" type="text/css" href="' . base_url($minified_file) . '">' . "\n";
    }

    /**
     * Render minified JavaScript (production mode)
     * @return string Minified JavaScript HTML tag
     */
    private function render_minified_js() {
        $js_hash = $this->generate_file_hash($this->js_files, 'js');
        $minified_file = $this->minified_path . 'app-' . $js_hash . '.min.js';
        $minified_path = FCPATH . $minified_file;

        if (!file_exists($minified_path) || $this->should_regenerate($minified_path)) {
            $this->create_minified_js($minified_path);
        }

        return '<script src="' . base_url($minified_file) . '"></script>' . "\n";
    }

    /**
     * Create minified CSS file
     * @param string $output_path Output file path
     */
    private function create_minified_css($output_path) {
        $combined_css = '';
        
        foreach ($this->css_files as $css) {
            $file_path = FCPATH . $css['file'];
            if (file_exists($file_path)) {
                $content = file_get_contents($file_path);
                $combined_css .= $this->minify_css($content) . "\n";
            }
        }

        file_put_contents($output_path, $combined_css);
    }

    /**
     * Create minified JavaScript file
     * @param string $output_path Output file path
     */
    private function create_minified_js($output_path) {
        $combined_js = '';
        
        foreach ($this->js_files as $js) {
            $file_path = FCPATH . $js['file'];
            if (file_exists($file_path)) {
                $content = file_get_contents($file_path);
                $combined_js .= $this->minify_js($content) . "\n";
            }
        }

        file_put_contents($output_path, $combined_js);
    }

    /**
     * Minify CSS content
     * @param string $css CSS content
     * @return string Minified CSS
     */
    private function minify_css($css) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove unnecessary whitespace
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        
        // Remove whitespace around specific characters
        $css = str_replace(array(' {', '{ ', ' }', '} ', '; ', ' ;', ': ', ' :', ', ', ' ,'), array('{', '{', '}', '}', ';', ';', ':', ':', ',', ','), $css);
        
        return trim($css);
    }

    /**
     * Minify JavaScript content (basic minification)
     * @param string $js JavaScript content
     * @return string Minified JavaScript
     */
    private function minify_js($js) {
        // Remove single-line comments (but preserve URLs)
        $js = preg_replace('/(?<!:)\/\/.*$/m', '', $js);
        
        // Remove multi-line comments
        $js = preg_replace('/\/\*[\s\S]*?\*\//', '', $js);
        
        // Remove unnecessary whitespace
        $js = preg_replace('/\s+/', ' ', $js);
        
        // Remove whitespace around specific characters
        $js = str_replace(array(' {', '{ ', ' }', '} ', '; ', ' ;', ': ', ' :', ', ', ' ,', ' = ', ' + ', ' - ', ' * ', ' / '), 
                          array('{', '{', '}', '}', ';', ';', ':', ':', ',', ',', '=', '+', '-', '*', '/'), $js);
        
        return trim($js);
    }

    /**
     * Generate hash for file list
     * @param array $files File list
     * @param string $type File type (css/js)
     * @return string Hash
     */
    private function generate_file_hash($files, $type) {
        $file_info = array();
        
        foreach ($files as $file) {
            $file_path = FCPATH . $file['file'];
            if (file_exists($file_path)) {
                $file_info[] = $file['file'] . ':' . filemtime($file_path);
            }
        }
        
        return substr(md5(implode('|', $file_info) . $type), 0, 8);
    }

    /**
     * Check if minified file should be regenerated
     * @param string $minified_path Minified file path
     * @return bool Should regenerate
     */
    private function should_regenerate($minified_path) {
        $minified_time = filemtime($minified_path);
        
        foreach ($this->css_files as $css) {
            $file_path = FCPATH . $css['file'];
            if (file_exists($file_path) && filemtime($file_path) > $minified_time) {
                return true;
            }
        }
        
        foreach ($this->js_files as $js) {
            $file_path = FCPATH . $js['file'];
            if (file_exists($file_path) && filemtime($file_path) > $minified_time) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Clean old minified files
     */
    public function clean_old_files() {
        $minified_dir = FCPATH . $this->minified_path;
        
        if (is_dir($minified_dir)) {
            $files = glob($minified_dir . '*.min.{css,js}', GLOB_BRACE);
            $current_time = time();
            
            foreach ($files as $file) {
                // Delete files older than 7 days
                if ($current_time - filemtime($file) > 7 * 24 * 3600) {
                    unlink($file);
                }
            }
        }
    }

    /**
     * Get asset loading configuration for different environments
     * @return array Asset configuration
     */
    public function get_asset_config() {
        $config = array(
            'minify' => ENVIRONMENT === 'production',
            'combine' => ENVIRONMENT === 'production',
            'cache_buster' => $this->cache_buster,
            'preload_critical' => true,
            'lazy_load_images' => true
        );
        
        return $config;
    }

    /**
     * Generate critical CSS for above-the-fold content
     * @param array $critical_css_files Critical CSS files
     * @return string Inline critical CSS
     */
    public function generate_critical_css($critical_css_files) {
        $critical_css = '';
        
        foreach ($critical_css_files as $file) {
            $file_path = FCPATH . $file;
            if (file_exists($file_path)) {
                $content = file_get_contents($file_path);
                $critical_css .= $this->minify_css($content);
            }
        }
        
        return '<style>' . $critical_css . '</style>';
    }

    /**
     * Generate preload links for important resources
     * @param array $preload_files Files to preload
     * @return string Preload HTML tags
     */
    public function generate_preload_links($preload_files) {
        $output = '';
        
        foreach ($preload_files as $file) {
            $file_path = base_url($file['src']) . '?v=' . $this->cache_buster;
            $as = $file['as'] ?? 'script';
            $type = isset($file['type']) ? ' type="' . $file['type'] . '"' : '';
            
            $output .= '<link rel="preload" href="' . $file_path . '" as="' . $as . '"' . $type . '>' . "\n";
        }
        
        return $output;
    }

    /**
     * Reset file queues
     */
    public function reset() {
        $this->css_files = array();
        $this->js_files = array();
    }
}