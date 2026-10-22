<?php

namespace App\Support;

/**
 * One safe-HTML cleaner for every rich-text box in the admin (inner pages, news & events).
 * Only the tags / attributes listed here survive; scripts, event handlers (onclick ...) and
 * javascript: links are removed. Normal formatting (and the class / style the pages already use) is kept.
 */
class HtmlCleaner
{
    /** Tags every editor may keep. */
    const BASIC = ['p', 'br', 'strong', 'b', 'em', 'i', 'u', 'ul', 'ol', 'li', 'a', 'h2', 'h3', 'h4', 'blockquote', 'span', 'hr'];

    /** Extra tags the inner-pages editor may keep. */
    const PAGE = ['h5', 'div', 'img', 'table', 'thead', 'tbody', 'tr', 'td', 'th', 'sub', 'sup'];

    /** Tags removed together with everything inside them. */
    const DROP = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'svg', 'math', 'link', 'meta', 'noscript', 'template', 'base'];

    const ATTRS = ['class', 'style', 'id', 'title', 'alt', 'width', 'height', 'align', 'valign', 'colspan', 'rowspan', 'border', 'cellpadding', 'cellspacing', 'bgcolor', 'href', 'src', 'target', 'rel'];

    public static function clean($html, $page = false)
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $allowed = $page ? array_merge(self::BASIC, self::PAGE) : self::BASIC;

        $previous = libxml_use_internal_errors(true);
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->loadHTML('<?xml encoding="UTF-8"><div>' . $html . '</div>', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->documentElement;
        if (!$root) {
            return '';
        }

        self::walk($root, $allowed);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return trim($out);
    }

    protected static function walk(\DOMNode $parent, array $allowed)
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if ($node instanceof \DOMComment || $node instanceof \DOMProcessingInstruction) {
                $parent->removeChild($node);
                continue;
            }
            if (!($node instanceof \DOMElement)) {
                continue; // plain text
            }

            $tag = strtolower($node->tagName);

            if (in_array($tag, self::DROP, true)) {
                $parent->removeChild($node);
                continue;
            }

            self::walk($node, $allowed);

            if (!in_array($tag, $allowed, true)) {
                // unknown tag: keep its text / children, drop the tag itself
                while ($node->firstChild) {
                    $parent->insertBefore($node->firstChild, $node);
                }
                $parent->removeChild($node);
                continue;
            }

            self::cleanAttributes($node);
        }
    }

    protected static function cleanAttributes(\DOMElement $el)
    {
        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->name);
            $keep = in_array($name, self::ATTRS, true);

            if ($keep && ($name === 'href' || $name === 'src')) {
                $keep = self::safeUrl($attr->value, $name === 'src');
            }
            if ($keep && $name === 'style' && preg_match('/expression\s*\(|javascript:|url\s*\(\s*[\'"]?\s*(javascript|data):/i', $attr->value)) {
                $keep = false;
            }
            if (!$keep) {
                $el->removeAttribute($attr->name);
            }
        }

        if (strtolower($el->getAttribute('target')) === '_blank') {
            $el->setAttribute('rel', 'noopener noreferrer');
        }
    }

    /** http(s), mailto, tel, #anchors and site-relative links only (no javascript: / data:). */
    protected static function safeUrl($url, $isImage)
    {
        // browsers ignore tabs / newlines / control characters inside a scheme
        $clean = preg_replace('/[\x00-\x20\x7f]+/', '', html_entity_decode($url, ENT_QUOTES, 'UTF-8'));

        if ($clean === '') {
            return true;
        }
        if (!preg_match('/^([a-z][a-z0-9+.\-]*):/i', $clean, $m)) {
            return true; // relative link
        }

        $schemes = $isImage ? ['http', 'https'] : ['http', 'https', 'mailto', 'tel'];

        return in_array(strtolower($m[1]), $schemes, true);
    }
}
