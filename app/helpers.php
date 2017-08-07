<?php

if (! function_exists('cb')) {
    /**
     * アセットファイルにキャッシュバスターをつけて返す.
     *
     * @param  string $path
     * @return string
     */
    function cb($path)
    {
        $realPath = public_path($path);
        if (! file_exists($realPath)) {
            return $path;
        }
        $time = filemtime($realPath);
        return $path . '?v=' . $time;
    }
}

if (! function_exists('dateToStr')) {
    /**
     * 日付をフォーマットして返す.
     * 1970-01-01 09:00:00 のような値も空文字として返す
     *
     * @param string $format
     * @param $date
     * @param $default
     * @return string
     */
    function dateToStr($format, $date, $default = '')
    {
        if (empty($date)
            || strpos($date, '1970') !== false
            || empty(with($time = strtotime($date)))
            || $time < strtotime('2000-01-01')
        ) {
            return is_string($default) ? $default : '';
        }
        return date($format, $time);
    }
}

if (! function_exists('bytesToHuman')) {
    /**
     * ファイルサイズを人が読みやすい文字にフォーマットして返す
     *
     * @param $bytes
     * @return string
     */
    function bytesToHuman($bytes)
    {
        if (! is_numeric($bytes)) {
            return $bytes;
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
