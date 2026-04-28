<?php

declare(strict_types=1);

use App\Core\Session;

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $projectRoot = str_replace('/public/index.php', '', $scriptPath);
        $projectRoot = rtrim($projectRoot, '/');
        
        if (empty($path)) {
            return $projectRoot;
        }
        
        return $projectRoot . '/' . ltrim($path, '/');
    }
}

if (!function_exists('view')) {
    function view(string $name, array $data = []): void
    {
        extract($data);
        $layout = __DIR__ . '/../Views/layouts/main.php';
        $view = __DIR__ . '/../Views/' . str_replace('.', '/', $name) . '.php';

        if (file_exists($layout)) {
            ob_start();
            if (file_exists($view)) {
                include $view;
            } else {
                echo "View [{$name}] not found.";
            }
            $content = ob_get_clean();
            include $layout;
        } else {
            if (file_exists($view)) {
                include $view;
            } else {
                echo "View [{$name}] not found.";
            }
        }
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header("Location: " . base_url($path));
        exit;
    }
}

if (!function_exists('json')) {
    function json(mixed $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

if (!function_exists('time_ago')) {
    function time_ago(string $datetime): string
    {
        $time = strtotime($datetime);
        $diff = time() - $time;
        
        if ($diff < 1) return 'just now';
        
        $intervals = [
            31536000 => 'year',
            2592000  => 'month',
            604800   => 'week',
            86400    => 'day',
            3600     => 'hour',
            60       => 'minute',
            1        => 'second'
        ];
        
        foreach ($intervals as $secs => $label) {
            $d = $diff / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $label . ($r > 1 ? 's' : '') . ' ago';
            }
        }
        
        return 'just now';
    }
}
