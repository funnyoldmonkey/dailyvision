<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?php echo $title ?? 'Daily Vision | Spiritual Lens'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/icons/icon-192.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/icons/apple-touch-icon.png'); ?>">
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="<?php echo base_url('manifest.json'); ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Daily Vision">
    <meta name="theme-color" content="#F9F8F4">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $og_url ?? base_url(); ?>">
    <meta property="og:title" content="<?php echo $og_title ?? 'Daily Vision | Spiritual Lens'; ?>">
    <meta property="og:description" content="<?php echo $og_description ?? 'Take a photo, receive a reflection. A spiritual lens for your daily journey.'; ?>">
    <meta property="og:image" content="<?php echo $og_image ?? base_url('assets/icons/icon-512.png'); ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $og_url ?? base_url(); ?>">
    <meta property="twitter:title" content="<?php echo $og_title ?? 'Daily Vision | Spiritual Lens'; ?>">
    <meta property="twitter:description" content="<?php echo $og_description ?? 'Take a photo, receive a reflection. A spiritual lens for your daily journey.'; ?>">
    <meta property="twitter:image" content="<?php echo $og_image ?? base_url('assets/icons/icon-512.png'); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&family=Playfair+Display:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#F9F8F4',
                        paper: '#FFFFFF',
                        sage: '#7D8471',
                        ink: '#2D2D2D',
                        beige: '#F0EDE4',
                    },
                    fontFamily: {
                        serif: ['Georgia', 'serif'],
                        sans: ['Helvetica Neue', 'Arial', 'sans-serif'],
                        display: ['Playfair Display', 'serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --bg: #F9F8F4;
            --paper: #FFFFFF;
            --sage: #7D8471;
            --ink: #2D2D2D;
            --beige: #F0EDE4;
            --f-serif: 'Georgia', serif;
            --f-sans: 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--ink);
            font-family: var(--f-sans);
            margin: 0;
            padding: 0;
            overflow: hidden; /* Prevent scrolling on main app */
            height: 100vh;
            width: 100vw;
        }

        .safe-area-inset-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }

        .sage-serif {
            font-family: var(--f-serif);
            color: var(--sage);
        }

        /* Minimal Loader */
        .loader {
            border: 4px solid var(--beige);
            border-top: 4px solid var(--sage);
            border-radius: 50%;
            width: 48px;
            height: 48px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Glassmorphism for reflection screen container */
        .reflection-container {
            background: #e0ddd5;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body class="flex flex-col">
    
    <?php echo $content ?? ''; ?>

    <script>
        const APP_URL = "<?php echo base_url(); ?>";
    </script>
    <script src="<?php echo base_url('assets/js/pwa.js?v=1.2'); ?>"></script>
    <script src="<?php echo base_url('assets/js/app.js?v=1.2'); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof App !== 'undefined') {
                App.init();
            }
        });
    </script>
</body>
</html>
