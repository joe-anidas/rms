<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pagination Helper Library
 * Provides pagination and lazy loading functionality for large datasets
 */
class Pagination_helper {

    protected $CI;
    protected $default_per_page = 20;
    protected $max_per_page = 100;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('pagination');
    }

    /**
     * Create pagination configuration
     * @param string $base_url Base URL for pagination links
     * @param int $total_rows Total number of records
     * @param int $per_page Records per page
     * @param int $uri_segment URI segment for page number
     * @return array Pagination configuration
     */
    public function create_pagination_config($base_url, $total_rows, $per_page = null, $uri_segment = 3) {
        if ($per_page === null) {
            $per_page = $this->default_per_page;
        }
        
        // Limit per_page to prevent performance issues
        if ($per_page > $this->max_per_page) {
            $per_page = $this->max_per_page;
        }

        $config = array(
            'base_url' => $base_url,
            'total_rows' => $total_rows,
            'per_page' => $per_page,
            'uri_segment' => $uri_segment,
            'use_page_numbers' => TRUE,
            'page_query_string' => FALSE,
            'query_string_segment' => 'page',
            
            // Bootstrap 5 styling
            'full_tag_open' => '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">',
            'full_tag_close' => '</ul></nav>',
            
            'first_link' => '&laquo; First',
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close' => '</li>',
            
            'last_link' => 'Last &raquo;',
            'last_tag_open' => '<li class="page-item">',
            'last_tag_close' => '</li>',
            
            'next_link' => 'Next &rsaquo;',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',
            
            'prev_link' => '&lsaquo; Previous',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            
            'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close' => '</span></li>',
            
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',
            
            'attributes' => array('class' => 'page-link')
        );

        return $config;
    }

    /**
     * Initialize pagination with configuration
     * @param array $config Pagination configuration
     * @return string Pagination links HTML
     */
    public function initialize_pagination($config) {
        $this->CI->pagination->initialize($config);
        return $this->CI->pagination->create_links();
    }

    /**
     * Get pagination offset based on current page
     * @param int $page Current page number
     * @param int $per_page Records per page
     * @return int Offset for database query
     */
    public function get_offset($page = 1, $per_page = null) {
        if ($per_page === null) {
            $per_page = $this->default_per_page;
        }
        
        $page = max(1, (int)$page);
        return ($page - 1) * $per_page;
    }

    /**
     * Create pagination info text
     * @param int $page Current page
     * @param int $per_page Records per page
     * @param int $total_rows Total records
     * @return string Pagination info text
     */
    public function get_pagination_info($page, $per_page, $total_rows) {
        $start = $this->get_offset($page, $per_page) + 1;
        $end = min($start + $per_page - 1, $total_rows);
        
        if ($total_rows == 0) {
            return "No records found";
        }
        
        return "Showing {$start} to {$end} of {$total_rows} entries";
    }

    /**
     * Generate AJAX pagination for lazy loading
     * @param string $ajax_url AJAX endpoint URL
     * @param array $params Additional parameters
     * @return string JavaScript code for AJAX pagination
     */
    public function generate_ajax_pagination($ajax_url, $params = array()) {
        $params_json = json_encode($params);
        
        return "
        <script>
        function loadPage(page) {
            const params = {$params_json};
            params.page = page;
            
            // Show loading indicator
            $('#content-container').addClass('loading');
            
            $.ajax({
                url: '{$ajax_url}',
                type: 'GET',
                data: params,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#content-container').html(response.content);
                        $('#pagination-container').html(response.pagination);
                        $('#pagination-info').html(response.pagination_info);
                        
                        // Update URL without page reload
                        const newUrl = new URL(window.location);
                        newUrl.searchParams.set('page', page);
                        window.history.pushState({}, '', newUrl);
                    } else {
                        alert('Error loading data: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error loading data. Please try again.');
                },
                complete: function() {
                    $('#content-container').removeClass('loading');
                }
            });
        }
        
        // Handle pagination link clicks
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            const page = href.match(/page=(\d+)/);
            if (page) {
                loadPage(page[1]);
            }
        });
        </script>";
    }

    /**
     * Create lazy loading configuration for infinite scroll
     * @param string $ajax_url AJAX endpoint URL
     * @param string $container_selector Container selector for new content
     * @param array $params Additional parameters
     * @return string JavaScript code for lazy loading
     */
    public function generate_lazy_loading($ajax_url, $container_selector = '#content-container', $params = array()) {
        $params_json = json_encode($params);
        
        return "
        <script>
        let currentPage = 1;
        let loading = false;
        let hasMoreData = true;
        
        function loadMoreData() {
            if (loading || !hasMoreData) return;
            
            loading = true;
            currentPage++;
            
            const params = {$params_json};
            params.page = currentPage;
            
            // Show loading indicator
            $('#loading-indicator').show();
            
            $.ajax({
                url: '{$ajax_url}',
                type: 'GET',
                data: params,
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.content) {
                        $('{$container_selector}').append(response.content);
                        hasMoreData = response.has_more;
                        
                        if (!hasMoreData) {
                            $('#loading-indicator').html('<p class=\"text-center text-muted\">No more data to load</p>');
                        }
                    } else {
                        hasMoreData = false;
                        $('#loading-indicator').html('<p class=\"text-center text-danger\">Error loading more data</p>');
                    }
                },
                error: function() {
                    hasMoreData = false;
                    $('#loading-indicator').html('<p class=\"text-center text-danger\">Error loading more data</p>');
                },
                complete: function() {
                    loading = false;
                    if (hasMoreData) {
                        $('#loading-indicator').hide();
                    }
                }
            });
        }
        
        // Infinite scroll implementation
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                loadMoreData();
            }
        });
        
        // Load more button implementation
        $(document).on('click', '#load-more-btn', function(e) {
            e.preventDefault();
            loadMoreData();
        });
        </script>";
    }

    /**
     * Get pagination parameters from request
     * @return array Pagination parameters
     */
    public function get_pagination_params() {
        $page = $this->CI->input->get('page') ?: 1;
        $per_page = $this->CI->input->get('per_page') ?: $this->default_per_page;
        
        // Validate and sanitize
        $page = max(1, (int)$page);
        $per_page = min($this->max_per_page, max(1, (int)$per_page));
        
        return array(
            'page' => $page,
            'per_page' => $per_page,
            'offset' => $this->get_offset($page, $per_page)
        );
    }

    /**
     * Create AJAX response for paginated data
     * @param array $data Data array
     * @param string $view_file View file to render
     * @param array $pagination_config Pagination configuration
     * @return array AJAX response array
     */
    public function create_ajax_response($data, $view_file, $pagination_config) {
        $content = $this->CI->load->view($view_file, array('data' => $data), TRUE);
        $pagination_links = $this->initialize_pagination($pagination_config);
        $pagination_info = $this->get_pagination_info(
            $pagination_config['current_page'] ?? 1,
            $pagination_config['per_page'],
            $pagination_config['total_rows']
        );
        
        return array(
            'success' => true,
            'content' => $content,
            'pagination' => $pagination_links,
            'pagination_info' => $pagination_info,
            'has_more' => ($pagination_config['current_page'] * $pagination_config['per_page']) < $pagination_config['total_rows']
        );
    }

    /**
     * Set default per page value
     * @param int $per_page Default per page value
     */
    public function set_default_per_page($per_page) {
        $this->default_per_page = max(1, min($this->max_per_page, (int)$per_page));
    }

    /**
     * Set maximum per page value
     * @param int $max_per_page Maximum per page value
     */
    public function set_max_per_page($max_per_page) {
        $this->max_per_page = max(1, (int)$max_per_page);
    }
}