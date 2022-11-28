<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class ApplicationMenuFormatService
{
    public function format(&$session_menus, &$data = null)
    {
        if (!$data) {
            return collect(\session()->get('menu'))->toArray();
        }
        foreach ($session_menus as $key => $item) {
            $session_menus[$key]['title'] = (is_array($item) && array_key_exists('title', $item)) ? $item['title'] : '';
            $session_menus[$key]['url'] = (is_array($item) && array_key_exists('url', $item)) ? $item['url'] : '';
            $session_menus[$key]['priority'] = (is_array($item) && array_key_exists('priority', $item)) ? (int)$item['priority'] : '';
            $session_menus[$key]['view_status'] = (is_array($item) && array_key_exists('view_status', $item)) && $item['view_status'] === 'false' ? false : true;
            if (\array_key_exists($key, $data)) {
                $data[$key]['title'] = (is_array($data[$key]) && array_key_exists('title', $data[$key])) ? $data[$key]['title'] : '';
                $data[$key]['url'] = (is_array($data[$key]) && array_key_exists('url', $data[$key])) ? $data[$key]['url'] : '';
                $data[$key]['priority'] = (is_array($data[$key]) && array_key_exists('priority', $data[$key])) ? (int)$data[$key]['priority'] : '';
                $data[$key]['view_status'] = (is_array($data[$key]) && array_key_exists('view_status', $data[$key])) && $data[$key]['view_status'] === 'false' ? false : true;
                if ($data[$key]['title'] == $session_menus[$key]['title'] && $session_menus[$key]['url'] == $data[$key]['url'] && $session_menus[$key]['priority'] == $data[$key]['priority']) {
                    $session_menus[$key]['view_status'] = $data[$key]['view_status'];
                }
            }
            
            if (count($session_menus[$key]['items'] ?? []) && count($data[$key]['items'] ?? [])) {
                $this->format($session_menus[$key]['items'], $data[$key]['items'],);
            }
        }
    }
}