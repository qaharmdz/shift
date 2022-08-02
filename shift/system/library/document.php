<?php

declare(strict_types=1);

namespace Shift\System\Library;

class Document
{
    protected $data = [];

    public function all()
    {
        return $this->data;
    }

    public function setTitle(string $title)
    {
        $this->data['title'] = $title;
    }

    public function getTitle($default = '')
    {
        return $this->data['title'] ?? $default;
    }

    /**
     * <meta $attribute="$value" content="$content">
     * <meta name="description" content="...">
     */
    public function addMeta(string $attribute, string $value, string $content)
    {
        $this->data['meta'][$attribute . '-' . $value] = [
            'attribute' => $attribute,
            'value'     => $value,
            'content'   => $content
        ];
    }

    public function getMetas()
    {
        return $this->data['meta'] ?? [];
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/link
     */
    public function addLink(string $rel, string $href, string $hreflang = '', string $type = '', string $media = '')
    {
        $this->data['link'][$href] = [
            'rel'       => $rel,
            'href'      => $href,
            'hreflang'  => $hreflang,
            'type'      => $type,
            'media'     => $media
        ];
    }

    public function getLinks()
    {
        return $this->data['link'] ?? [];
    }

    public function addStyle(string $href, string $rel = 'stylesheet', string $media = 'screen')
    {
        $this->data['style'][$href] = [
            'href'  => $href,
            'rel'   => $rel,
            'media' => $media
        ];
    }

    public function getStyles()
    {
        return $this->data['style'] ?? [];
    }

    /**
     * @param string $href
     * @param string $place Options: header, footer
     */
    public function addScript(string $href, string $placement = 'header')
    {
        $this->data[$placement]['script'][$href] = $href;
    }

    public function getScripts(string $placement = 'header')
    {
        return $this->data[$placement]['script'] ?? [];
    }

    public function addAsset(string $name, array $asset)
    {
        $this->data['asset'][$name] = array_replace(['style' => [], 'script' => []], $asset);
    }

    public function getAsset(string $name)
    {
        return $this->data['asset'][$name] ?? [];
    }

    public function loadAsset(string $name)
    {
        if (!empty($this->data['asset'][$name])) {
            foreach ($this->data['asset'][$name] as $type => $assets) {
                if ($type == 'style') {
                    foreach ($assets as $asset) {
                        $this->addStyle($asset);
                    }
                }

                if ($type == 'script') {
                    foreach ($assets as $asset) {
                        if (is_array($asset)) {
                            $this->addScript($asset[1], $asset[0]);
                        } else {
                            $this->addScript($asset, 'header');
                        }
                    }
                }
            }
        }
    }

    /**
     * Node is a general purpose storage.
     */
    public function addNode(string $name, $value)
    {
        $type = is_array($value) ? [] : '';
        $node = $this->data['nodes'][$name] ?? $type;

        if (is_array($node)) {
            $this->setNode($name, array_unique(array_merge($node, (array)$value), SORT_REGULAR));
        } else {
            $this->setNode($name, $value);
        }
    }

    public function setNode(string $name, $value)
    {
        $this->data['nodes'][$name] = $value;
    }

    public function getNode(string $name, $default = null)
    {
        return $this->data['nodes'][$name] ?? $default;
    }
}
