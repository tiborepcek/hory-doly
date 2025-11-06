<?php

// Manually include the Parsedown library file
require_once 'parsedown.php';

// Specify the path to your Markdown file
$markdownFile = 'readme.md';

// Check if the file exists and is readable
if (!is_readable($markdownFile)) {
    http_response_code(500);
    echo "Error: Cannot read the Markdown file at '{$markdownFile}'.";
    exit;
}

// Read the Markdown content from the file
$markdownContent = file_get_contents($markdownFile);
if ($markdownContent === false) {
    http_response_code(500);
    echo "Error: Failed to read the content of the Markdown file.";
    exit;
}

// Create a new Parsedown instance
$parsedown = new Parsedown();

// By default, Parsedown is safe and escapes HTML.
// If you want to be extra cautious, you can enable safe mode,
// which prevents any raw HTML in the Markdown from being rendered.
// $parsedown->setSafeMode(true);

// Convert the Markdown to HTML
$html = $parsedown->text($markdownContent);

// --- New code to wrap in a full HTML document ---

// Extract the first H1 to use as the page title for better SEO and browser tab text
$title = 'Markdown Document'; // A sensible default
if (preg_match('/<h1[^>]*>(.*?)<\/h1>/i', $html, $matches)) {
    $title = strip_tags($matches[1]);
}

// Output the full HTML document with embedded dark mode CSS
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        :root {
            --bg-color: #121212;
            --text-color: #e0e0e0;
            --heading-color: #ffffff;
            --link-color: #bb86fc;
            --border-color: #333333;
            --code-bg-color: #2c2c2c;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.7;
            margin: 0 auto;
            max-width: 800px;
            padding: 2em;
        }
        h1, h2, h3, h4, h5, h6 {
            color: var(--heading-color);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.3em;
        }
        a {
            color: var(--link-color);
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php echo $html; ?>
</body>
</html>