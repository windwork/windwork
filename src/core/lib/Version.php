<?php
/**
 * Windwork
 * 
 * 一个用于快速开发高并发Web应用的轻量级PHP框架
 * 
 * @copyright Copyright (c) 2008-2017 Windwork Team. (http://www.windwork.org)
 * @license   http://opensource.org/licenses/MIT
 */
namespace wf\core;

/**
 * Windwork框架版本信息
 * 
 * @package     wf.core
 * @author      cm <cmpan@qq.com>
 * @link        http://docs.windwork.org/manual/wf.core.version.html
 * @since       0.1.0
 */
final class Version 
{
    /**
     * 当前Windwork大版本号
     * @var string
     */
    const VERSION = '0.6';
    
    /**
     * 
     * @var array
     */
    private static $componentList = [
        'cache',
        'captcha',
        'core',
        'crypt',
        'db',
        'image',
        'logger',
        'mailer',
        'model',
        'pager',
        'route',
        'storage',
        'template',
        'util',
        'web',
        'widget',
    ];
    
    /**
     * 
     * @param string $component = null 如果指定组件则只返回一个组件的版本信息
     * @param string $isCache
     */
    public static function getComponentVersion($component = null, $isCache = true)
    {
        if ($component && !in_array($component, static::$componentList)) {
            $msg = '$component param not allow to be ' . $component . ', It\'s only allow ' 
                . implode(',', static::$componentList[$component]) . '.';
            throw new \wf\core\Exception($msg);
        }
        
        if ($isCache && function_exists('\wfCache') && false != ($v = \wfCache()->read('wf/version/allComponentVersion'))) {            
            return $component ? $v['component'][$component] : $v;
        }
        
        $v = [
            'release' => '',
            'component' => [],
        ];
        
        $wfDir = dirname(dirname(__DIR__));
        
        foreach (static::$componentList as $cmp) {
            if($cmp[0] == '.') {
                continue;
            }
            
            $composerFile = "{$wfDir}/{$cmp}/composer.json";
            if(!is_file($composerFile)) {
                continue;
            }
            
            $composerJson = @file_get_contents($composerFile);            
            if (!$composerJson) {
                continue;
            }
            
            $versionObj = json_decode($composerJson, false);
            if (empty($versionObj->version)) {
                continue;
            }
            
            $v['component'][$cmp] = [
                'version' => $versionObj->version,
                'time' => (string)@$versionObj->time,
            ];
            
            if (empty($versionObj->time)) {
                continue;
            }
            
            if (!$v['release'] || strtotime($versionObj->time) > strtotime($v['release'])) {
                $v['release'] = $versionObj->time;                        
            }
        }
        
        if ($isCache && function_exists('\wfCache')) {
            \wfCache()->write('wf/version/allComponentVersion', $v);
        }
        
        return $component ? $v['component'][$component] : $v;
    }
    
    /**
     * 获取本地Windwork组件最后发布时间
     * @param string $component = null 如果为null则从所有组件中挑选最大的
     * @param string $isCache = true 是否缓存
     */
    public static function getReleaseTime($component = null, $isCache = true)
    {
        $version = static::getComponentVersion($component, $isCache);
        
        if ($component) {
            return $version['time'];
        } else {
            return $version['release'];
        }
    }
    
    
    /**
     * 获取远程Windwork组件最后发布时间
     * 
     * @param string $component = null 如果指定组件则只返回一个组件的版本信息
     * @param string $isCache
     */
    public static function getRemoteComponentVersion($component = null, $isCache = true)
    {
        if ($component && !in_array($component, static::$componentList)) {
            $msg = '$component param not allow to be ' . $component . ', It\'s only allow '
                . implode(',', static::$componentList[$component]) . '.';
                throw new \wf\core\Exception($msg);
        }
        
        // 从缓存读取，则读取全部组件
        if (!$component || $isCache) {
            if ($isCache && function_exists('\wfCache') && false != ($v = \wfCache()->read('wf/version/allRemoteComponentVersion'))) {
                return $component ? $v['component'][$component] : $v;
            }
            
            $v = [
                'release' => '',
                'component' => [],
            ];
            
            foreach (static::$componentList as $cmp) {
                if($cmp[0] == '.') {
                    continue;
                }
                
                $composerFile = "https://raw.githubusercontent.com/windwork/wf-{$cmp}/master/composer.json";
                $composerJson = @file_get_contents($composerFile);
                
                if (!$composerJson) {
                    continue;
                }
                
                $versionObj = json_decode($composerJson, false);
                if (empty($versionObj->version)) {
                    continue;
                }
                
                $v['component'][$cmp] = [
                    'version' => $versionObj->version,
                    'time' => (string)@$versionObj->time,
                ];
                
                if (empty($versionObj->time)) {
                    continue;
                }
                
                if (!$v['release'] || strtotime($versionObj->time) > strtotime($v['release'])) {
                    $v['release'] = $versionObj->time;
                }
            }
            
            if (!$v['component']) {
                throw new \wf\core\Exception('can\'t read windwork component composer.json file from github.com');
            }
            
            if ($isCache && function_exists('\wfCache')) {
                \wfCache()->write('wf/version/allComponentVersion', $v);
            }
            
            return $component ? $v['component'][$component] : $v;
            
        }
        
        // 指定$component并且不从缓存读取        
        $composerFile = "https://github.com/windwork/wf-{$cmp}/composer.json";
        $composerJson = @file_get_contents($composerFile);
        if (!$composerJson || !($versionObj = @json_decode($composerJson, false))) {
            throw new \wf\core\Exception('can\'t read windwork component composer.json file from github.com');
        }
        
        return [
            'version' => $versionObj->version,
            'time' => (string)@$versionObj->time,
        ];
    }
    
    /**
     * 所有组件在GitHub上最后发布的时间
     * 
     * @param string $component = null 如果为null则从所有组件中挑选最大的
     * @param string $isCache = true 是否缓存
     */
    public static function getRemoteReleaseTime($component = null, $isCache = true)
    {
        $version = static::getRemoteComponentVersion($component, $isCache);
        
        if ($component) {
            return $version['time'];
        } else {
            return $version['release'];
        }
    }
}
