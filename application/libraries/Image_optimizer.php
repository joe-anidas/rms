<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Image Optimizer Library
 * Provides image optimization and compression for uploaded files
 */
class Image_optimizer {

    protected $CI;
    protected $upload_path = 'uploads/';
    protected $optimized_path = 'uploads/optimized/';
    protected $thumbnail_path = 'uploads/thumbnails/';
    protected $max_width = 1920;
    protected $max_height = 1080;
    protected $jpeg_quality = 85;
    protected $png_compression = 6;
    protected $webp_quality = 80;

    public function __construct($params = array()) {
        $this->CI =& get_instance();
        $this->CI->load->library('image_lib');
        
        // Override default settings if provided
        if (isset($params['max_width'])) {
            $this->max_width = $params['max_width'];
        }
        if (isset($params['max_height'])) {
            $this->max_height = $params['max_height'];
        }
        if (isset($params['jpeg_quality'])) {
            $this->jpeg_quality = $params['jpeg_quality'];
        }
        
        // Create directories if they don't exist
        $this->create_directories();
    }

    /**
     * Optimize uploaded image
     * @param string $source_path Source image path
     * @param array $options Optimization options
     * @return array Result with optimized file info
     */
    public function optimize_image($source_path, $options = array()) {
        try {
            if (!file_exists($source_path)) {
                return array('success' => false, 'message' => 'Source file not found');
            }

            $file_info = pathinfo($source_path);
            $extension = strtolower($file_info['extension']);
            
            // Check if file is an image
            if (!$this->is_valid_image($source_path, $extension)) {
                return array('success' => false, 'message' => 'Invalid image file');
            }

            // Generate optimized filename
            $optimized_filename = $file_info['filename'] . '_opt.' . $extension;
            $optimized_path = FCPATH . $this->optimized_path . $optimized_filename;

            // Get image dimensions
            $image_info = getimagesize($source_path);
            $original_width = $image_info[0];
            $original_height = $image_info[1];

            // Determine if resizing is needed
            $needs_resize = ($original_width > $this->max_width || $original_height > $this->max_height);
            
            if ($needs_resize) {
                $new_dimensions = $this->calculate_resize_dimensions($original_width, $original_height);
                $resize_result = $this->resize_image($source_path, $optimized_path, $new_dimensions);
                
                if (!$resize_result['success']) {
                    return $resize_result;
                }
            } else {
                // Just compress without resizing
                $compress_result = $this->compress_image($source_path, $optimized_path, $extension);
                
                if (!$compress_result['success']) {
                    return $compress_result;
                }
            }

            // Generate thumbnail if requested
            $thumbnail_path = null;
            if (isset($options['create_thumbnail']) && $options['create_thumbnail']) {
                $thumbnail_result = $this->create_thumbnail($optimized_path, $options);
                if ($thumbnail_result['success']) {
                    $thumbnail_path = $thumbnail_result['thumbnail_path'];
                }
            }

            // Generate WebP version if supported and requested
            $webp_path = null;
            if (isset($options['create_webp']) && $options['create_webp'] && function_exists('imagewebp')) {
                $webp_result = $this->create_webp_version($optimized_path);
                if ($webp_result['success']) {
                    $webp_path = $webp_result['webp_path'];
                }
            }

            // Calculate file size reduction
            $original_size = filesize($source_path);
            $optimized_size = filesize($optimized_path);
            $size_reduction = round((($original_size - $optimized_size) / $original_size) * 100, 2);

            return array(
                'success' => true,
                'original_path' => $source_path,
                'optimized_path' => $optimized_path,
                'thumbnail_path' => $thumbnail_path,
                'webp_path' => $webp_path,
                'original_size' => $original_size,
                'optimized_size' => $optimized_size,
                'size_reduction' => $size_reduction,
                'dimensions' => array(
                    'width' => $needs_resize ? $new_dimensions['width'] : $original_width,
                    'height' => $needs_resize ? $new_dimensions['height'] : $original_height
                )
            );

        } catch (Exception $e) {
            return array('success' => false, 'message' => 'Optimization failed: ' . $e->getMessage());
        }
    }

    /**
     * Resize image to fit within maximum dimensions
     * @param string $source_path Source image path
     * @param string $output_path Output image path
     * @param array $dimensions New dimensions
     * @return array Result
     */
    private function resize_image($source_path, $output_path, $dimensions) {
        $config = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $output_path,
            'maintain_ratio' => TRUE,
            'width' => $dimensions['width'],
            'height' => $dimensions['height'],
            'quality' => $this->jpeg_quality
        );

        $this->CI->image_lib->clear();
        $this->CI->image_lib->initialize($config);

        if ($this->CI->image_lib->resize()) {
            return array('success' => true, 'resized_path' => $output_path);
        } else {
            return array('success' => false, 'message' => $this->CI->image_lib->display_errors());
        }
    }

    /**
     * Compress image without resizing
     * @param string $source_path Source image path
     * @param string $output_path Output image path
     * @param string $extension File extension
     * @return array Result
     */
    private function compress_image($source_path, $output_path, $extension) {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return $this->compress_jpeg($source_path, $output_path);
                
            case 'png':
                return $this->compress_png($source_path, $output_path);
                
            case 'gif':
                // For GIF, just copy the file as compression might break animation
                if (copy($source_path, $output_path)) {
                    return array('success' => true, 'compressed_path' => $output_path);
                } else {
                    return array('success' => false, 'message' => 'Failed to copy GIF file');
                }
                
            default:
                return array('success' => false, 'message' => 'Unsupported image format');
        }
    }

    /**
     * Compress JPEG image
     * @param string $source_path Source image path
     * @param string $output_path Output image path
     * @return array Result
     */
    private function compress_jpeg($source_path, $output_path) {
        $image = imagecreatefromjpeg($source_path);
        
        if ($image === false) {
            return array('success' => false, 'message' => 'Failed to create image from JPEG');
        }

        // Enable interlacing for progressive JPEG
        imageinterlace($image, true);
        
        $result = imagejpeg($image, $output_path, $this->jpeg_quality);
        imagedestroy($image);

        if ($result) {
            return array('success' => true, 'compressed_path' => $output_path);
        } else {
            return array('success' => false, 'message' => 'Failed to save compressed JPEG');
        }
    }

    /**
     * Compress PNG image
     * @param string $source_path Source image path
     * @param string $output_path Output image path
     * @return array Result
     */
    private function compress_png($source_path, $output_path) {
        $image = imagecreatefrompng($source_path);
        
        if ($image === false) {
            return array('success' => false, 'message' => 'Failed to create image from PNG');
        }

        // Preserve transparency
        imagealphablending($image, false);
        imagesavealpha($image, true);
        
        $result = imagepng($image, $output_path, $this->png_compression);
        imagedestroy($image);

        if ($result) {
            return array('success' => true, 'compressed_path' => $output_path);
        } else {
            return array('success' => false, 'message' => 'Failed to save compressed PNG');
        }
    }

    /**
     * Create thumbnail image
     * @param string $source_path Source image path
     * @param array $options Thumbnail options
     * @return array Result
     */
    public function create_thumbnail($source_path, $options = array()) {
        $thumb_width = isset($options['thumb_width']) ? $options['thumb_width'] : 150;
        $thumb_height = isset($options['thumb_height']) ? $options['thumb_height'] : 150;
        
        $file_info = pathinfo($source_path);
        $thumbnail_filename = $file_info['filename'] . '_thumb.' . $file_info['extension'];
        $thumbnail_path = FCPATH . $this->thumbnail_path . $thumbnail_filename;

        $config = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $thumbnail_path,
            'maintain_ratio' => TRUE,
            'width' => $thumb_width,
            'height' => $thumb_height,
            'quality' => $this->jpeg_quality
        );

        $this->CI->image_lib->clear();
        $this->CI->image_lib->initialize($config);

        if ($this->CI->image_lib->resize()) {
            return array('success' => true, 'thumbnail_path' => $thumbnail_path);
        } else {
            return array('success' => false, 'message' => $this->CI->image_lib->display_errors());
        }
    }

    /**
     * Create WebP version of image
     * @param string $source_path Source image path
     * @return array Result
     */
    public function create_webp_version($source_path) {
        if (!function_exists('imagewebp')) {
            return array('success' => false, 'message' => 'WebP not supported');
        }

        $file_info = pathinfo($source_path);
        $webp_filename = $file_info['filename'] . '.webp';
        $webp_path = dirname($source_path) . '/' . $webp_filename;

        $extension = strtolower($file_info['extension']);
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($source_path);
                break;
            case 'png':
                $image = imagecreatefrompng($source_path);
                break;
            case 'gif':
                $image = imagecreatefromgif($source_path);
                break;
            default:
                return array('success' => false, 'message' => 'Unsupported format for WebP conversion');
        }

        if ($image === false) {
            return array('success' => false, 'message' => 'Failed to create image resource');
        }

        $result = imagewebp($image, $webp_path, $this->webp_quality);
        imagedestroy($image);

        if ($result) {
            return array('success' => true, 'webp_path' => $webp_path);
        } else {
            return array('success' => false, 'message' => 'Failed to create WebP image');
        }
    }

    /**
     * Calculate new dimensions while maintaining aspect ratio
     * @param int $original_width Original width
     * @param int $original_height Original height
     * @return array New dimensions
     */
    private function calculate_resize_dimensions($original_width, $original_height) {
        $ratio = min($this->max_width / $original_width, $this->max_height / $original_height);
        
        return array(
            'width' => round($original_width * $ratio),
            'height' => round($original_height * $ratio)
        );
    }

    /**
     * Check if file is a valid image
     * @param string $file_path File path
     * @param string $extension File extension
     * @return bool Is valid image
     */
    private function is_valid_image($file_path, $extension) {
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        
        if (!in_array($extension, $allowed_extensions)) {
            return false;
        }

        $image_info = getimagesize($file_path);
        return $image_info !== false;
    }

    /**
     * Create necessary directories
     */
    private function create_directories() {
        $directories = array(
            FCPATH . $this->upload_path,
            FCPATH . $this->optimized_path,
            FCPATH . $this->thumbnail_path
        );

        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }

    /**
     * Batch optimize images in a directory
     * @param string $directory Directory path
     * @param array $options Optimization options
     * @return array Results
     */
    public function batch_optimize($directory, $options = array()) {
        $results = array();
        $files = glob($directory . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        foreach ($files as $file) {
            $result = $this->optimize_image($file, $options);
            $results[] = array(
                'file' => basename($file),
                'result' => $result
            );
        }

        return $results;
    }

    /**
     * Clean up old optimized images
     * @param int $days_old Days old threshold
     */
    public function cleanup_old_images($days_old = 30) {
        $directories = array(
            FCPATH . $this->optimized_path,
            FCPATH . $this->thumbnail_path
        );

        $cutoff_time = time() - ($days_old * 24 * 3600);

        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $files = glob($dir . '*');
                foreach ($files as $file) {
                    if (is_file($file) && filemtime($file) < $cutoff_time) {
                        unlink($file);
                    }
                }
            }
        }
    }

    /**
     * Get image optimization statistics
     * @return array Statistics
     */
    public function get_optimization_stats() {
        $stats = array(
            'total_optimized' => 0,
            'total_original_size' => 0,
            'total_optimized_size' => 0,
            'total_savings' => 0
        );

        $optimized_dir = FCPATH . $this->optimized_path;
        if (is_dir($optimized_dir)) {
            $files = glob($optimized_dir . '*');
            $stats['total_optimized'] = count($files);
            
            foreach ($files as $file) {
                if (is_file($file)) {
                    $stats['total_optimized_size'] += filesize($file);
                }
            }
        }

        return $stats;
    }

    /**
     * Generate responsive image HTML
     * @param string $image_path Image path
     * @param array $options Options (alt, class, sizes, etc.)
     * @return string HTML
     */
    public function generate_responsive_image($image_path, $options = array()) {
        $file_info = pathinfo($image_path);
        $base_name = $file_info['filename'];
        $extension = $file_info['extension'];
        
        // Check for WebP version
        $webp_path = str_replace('.' . $extension, '.webp', $image_path);
        $has_webp = file_exists(FCPATH . $webp_path);
        
        $alt = isset($options['alt']) ? $options['alt'] : '';
        $class = isset($options['class']) ? ' class="' . $options['class'] . '"' : '';
        $loading = isset($options['loading']) ? $options['loading'] : 'lazy';
        
        $html = '<picture>';
        
        if ($has_webp) {
            $html .= '<source srcset="' . base_url($webp_path) . '" type="image/webp">';
        }
        
        $html .= '<img src="' . base_url($image_path) . '" alt="' . $alt . '"' . $class . ' loading="' . $loading . '">';
        $html .= '</picture>';
        
        return $html;
    }
}