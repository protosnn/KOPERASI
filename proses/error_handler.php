<?php
/**
 * ERROR HANDLER & 404 ROUTER
 * File ini menangani semua error 404 dan redirects di aplikasi
 * 
 * Cara penggunaan:
 * 1. Include file ini di .htaccess atau di index.php
 * 2. Atau panggil header() untuk redirect ke 404.php
 */

// Fungsi untuk handle 404 error
function show_404($page = '', $reason = '') {
    http_response_code(404);
    
    // Set headers
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Store error details dalam session untuk logging
    session_start();
    $_SESSION['error_404'] = [
        'page' => $page,
        'reason' => $reason,
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
    ];
    
    // Redirect ke halaman 404
    if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) {
        header('Location: /koperasi/admin/404.php?page=' . urlencode($page));
    } else if (strpos($_SERVER['REQUEST_URI'], '/anggota/') !== false) {
        header('Location: /koperasi/admin/404.php?page=' . urlencode($page));
    } else {
        header('Location: /koperasi/admin/404.php?page=' . urlencode($page));
    }
    exit();
}

// Fungsi untuk check jika file/halaman valid
function is_valid_page($page_name) {
    $valid_pages = [
        'dashboard',
        'anggota',
        'pinjaman',
        'simpanan',
        'rekap',
        'home'
    ];
    
    return in_array(strtolower($page_name), $valid_pages);
}

// Fungsi untuk redirect dengan message
function redirect_with_error($url, $message = '') {
    session_start();
    if ($message) {
        $_SESSION['error_message'] = $message;
    }
    header('Location: ' . $url);
    exit();
}

// Fungsi untuk redirect dengan success message
function redirect_with_success($url, $message = '') {
    session_start();
    if ($message) {
        $_SESSION['success_message'] = $message;
    }
    header('Location: ' . $url);
    exit();
}

// Custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (strpos($errfile, 'admin') !== false || strpos($errfile, 'anggota') !== false) {
        session_start();
        $_SESSION['php_error'] = [
            'errno' => $errno,
            'errstr' => $errstr,
            'errfile' => $errfile,
            'errline' => $errline
        ];
        
        // Untuk development, tampilkan error
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            echo '<h1>PHP Error</h1>';
            echo '<p>Error #' . $errno . ': ' . $errstr . '</p>';
            echo '<p>File: ' . $errfile . ' (Line: ' . $errline . ')</p>';
        } else {
            // Untuk production, redirect ke 404
            show_404('error', 'PHP Error occurred');
        }
    }
});

// Register shutdown function untuk catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && $error['type'] === E_ERROR) {
        session_start();
        $_SESSION['fatal_error'] = [
            'type' => $error['type'],
            'message' => $error['message'],
            'file' => $error['file'],
            'line' => $error['line']
        ];
        
        if (defined('ENVIRONMENT') && ENVIRONMENT !== 'development') {
            // Redirect ke 404
            header('Location: /koperasi/admin/404.php');
        }
    }
});

?>
