<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class DocumentationController extends Controller
{
    public function index(): View
    {
        $docsPath = base_path('docs');
        $documents = [];

        if (File::exists($docsPath)) {
            $files = File::files($docsPath);
            
            foreach ($files as $file) {
                if ($file->getExtension() === 'md') {
                    $documents[] = [
                        'name' => $file->getFilenameWithoutExtension(),
                        'filename' => $file->getFilename(),
                        'path' => $file->getPathname(),
                        'title' => $this->formatTitle($file->getFilenameWithoutExtension()),
                    ];
                }
            }
        }

        // Sort documents by name
        usort($documents, function ($a, $b) {
            return strcmp($a['title'], $b['title']);
        });

        return view('admin.documentation.index', compact('documents'));
    }

    public function show(string $doc): View
    {
        $docPath = base_path("docs/{$doc}.md");

        if (!File::exists($docPath)) {
            abort(404, 'Documentation not found');
        }

        $content = File::get($docPath);
        $title = $this->formatTitle($doc);

        // Convert markdown to HTML (basic conversion)
        $html = $this->markdownToHtml($content);

        return view('admin.documentation.show', compact('html', 'title', 'doc'));
    }

    protected function formatTitle(string $filename): string
    {
        // Convert filename to readable title
        // e.g., "whatsapp-catalog-setup" -> "WhatsApp Catalog Setup"
        return ucwords(str_replace(['-', '_'], ' ', $filename));
    }

    protected function markdownToHtml(string $markdown): string
    {
        // Try to use Parsedown if available, otherwise use basic conversion
        if (class_exists(\Parsedown::class)) {
            $parsedown = new \Parsedown();
            return $parsedown->text($markdown);
        }
        
        // Basic markdown to HTML conversion
        $html = $markdown;
        
        // Code blocks first (before other processing)
        $html = preg_replace_callback('/```(\w+)?\n(.*?)```/s', function ($matches) {
            $code = htmlspecialchars($matches[2], ENT_QUOTES, 'UTF-8');
            return '<pre><code>' . $code . '</code></pre>';
        }, $html);
        
        // Headers
        $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $html);
        $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^#### (.+)$/m', '<h4>$1</h4>', $html);
        $html = preg_replace('/^##### (.+)$/m', '<h5>$1</h5>', $html);
        $html = preg_replace('/^###### (.+)$/m', '<h6>$1</h6>', $html);
        
        // Bold (avoid matching inside code blocks)
        $html = preg_replace('/(?<!`)\*\*(.+?)\*\*(?!`)/', '<strong>$1</strong>', $html);
        
        // Italic (avoid matching inside code blocks)
        $html = preg_replace('/(?<!`)\*(?!\*)(.+?)(?<!\*)\*(?!`)/', '<em>$1</em>', $html);
        
        // Inline code (avoid already processed code blocks)
        $html = preg_replace_callback('/(?<!`)`([^`]+)`(?!`)/', function ($matches) {
            return '<code>' . htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8') . '</code>';
        }, $html);
        
        // Links
        $html = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>', $html);
        
        // Ordered lists
        $html = preg_replace_callback('/^(\d+)\. (.+)$/m', function ($matches) {
            return '<li>' . $matches[2] . '</li>';
        }, $html);
        
        // Unordered lists
        $html = preg_replace('/^[\*\-] (.+)$/m', '<li>$1</li>', $html);
        
        // Wrap consecutive list items in ul/ol tags
        $html = preg_replace_callback('/(<li>.*?<\/li>\s*)+/s', function ($matches) {
            $content = $matches[0];
            // Check if it's an ordered list (contains numbers)
            if (preg_match('/^\d+\./', $matches[0])) {
                return '<ol>' . $content . '</ol>';
            }
            return '<ul>' . $content . '</ul>';
        }, $html);
        
        // Split into paragraphs (double newlines)
        $paragraphs = preg_split('/\n\s*\n/', $html);
        $html = '';
        foreach ($paragraphs as $para) {
            $para = trim($para);
            if (empty($para)) {
                continue;
            }
            // Don't wrap if it's already a block element
            if (preg_match('/^<(h[1-6]|ul|ol|pre|blockquote)/', $para)) {
                $html .= $para . "\n";
            } else {
                $html .= '<p>' . $para . '</p>' . "\n";
            }
        }
        
        // Clean up
        $html = preg_replace('/<p>\s*<\/p>/', '', $html);
        $html = preg_replace('/<p>(<[h|u|o|p|pre])/', '$1', $html);
        $html = preg_replace('/(<\/[h|u|o|p|pre|li]>)<\/p>/', '$1', $html);
        
        return trim($html);
    }
}

