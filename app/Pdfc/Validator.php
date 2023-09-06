<?php
declare(strict_types=1);


namespace Pdfc;

class Validator
{
    public static function isValidRequest(array $request, $ip)
    {
        if (empty($request['content']) || !is_string($request['content'])) {
            return false;
        }

        if (!self::isIpAllow($ip)) {
            return false;
        }

        if (self::isContentHasStopWordForIp($request['content'], $ip) || self::isContentHasStopWord($request['content'])) {
            return false;
        }

        return true;
    }

    private static function isIpAllow($ip)
    {
        global $config;

        if (!empty($config['allowed_ips'])) {
            return in_array($ip, $config['allowed_ips'], true);
        }

        if (!empty($config['denied_ips'])) {
            return !in_array($ip, $config['denied_ips'], true);
        }

        return true;
    }

    private static function isContentHasStopWord($content)
    {
        global $config;

        if (empty($config['stop_words'])) {
            return false;
        }

        foreach ($config['stop_words'] as $word) {
            if (stripos($content, $word) !== false) {
                return true;
            }
        }

        return false;
    }

    private static function isContentHasStopWordForIp($content, $ip)
    {
        global $config;

        if (empty($config['ip_stop_words'][$ip])) {
            return false;
        }

        foreach ($config['ip_stop_words'][$ip] as $word) {
            if (stripos($content, $word) !== false) {
                return true;
            }
        }

        return false;
    }
}