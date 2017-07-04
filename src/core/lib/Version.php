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
    const VERSION = '0.6.0';
    
    const RELEASE = '2017-07-03 17:30:00';
        
    /**
     * 所有组件在GitHub上最后发布的时间
     * 
     * @param string $component = null 如果为null则从所有组件中挑选最大的
     * @param string $isCache = true 是否缓存
     */
    public static function getLatest()
    {
        $composerFile = "https://raw.githubusercontent.com/windwork/wf/master/composer.json";
        $composerJson = @file_get_contents($composerFile);
        
        if (!$composerJson || (false == $composerObj = json_decode($composerJson)) || !$composerObj->version) {
            throw new Exception('can\'t read composer file: ' . $composerFile);
        }
        
        $composerObj = json_decode($composerJson);
        if (!$composerObj || !$composerObj->version) {
            throw new Exception('composer.json data error: ' . $composerJson);
        }
        
        $latest = [
            'version' => $composerObj->version,
            'release' => @$composerObj->time,
        ];
        
        return $latest;
    }
}
