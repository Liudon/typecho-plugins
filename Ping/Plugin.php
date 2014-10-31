<?php
/**
 * 通知博客搜索引擎及时进行抓取、更新
 *
 * @package Ping
 * @author Liudon(i.mu@qq.com)
 * @version 1.0.1
 * @link http://www.liudon.org
 */
class Ping_Plugin implements Typecho_Plugin_Interface {

    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Contents_Post_Edit')->finishPublish = array('Ping_Plugin', 'ping');
    }

    public static function deactivate(){}

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $servers = <<<EOF
http://blogsearch.google.com/ping/RPC2
http://ping.baidu.com/ping/RPC2
EOF;
        $name = new Typecho_Widget_Helper_Form_Element_Textarea('servers', NULL, $servers, _t('Ping服务URL'), _t('一行一个'));
        $form->addInput($name);
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    public static function ping($content, $obj) {

        $servers = Helper::options()->plugin('Ping')->servers;

        if ($servers) {
            $servers = explode("\r\n", $servers);
            foreach ($servers as $server) {
                $server = trim($server);
                if (!$server) {
                    continue;
                }
                $validator = new Typecho_Validate();
                if (!$validator->url($server)) {
                    continue;
                }
                try {
                    $client = new IXR_Client($server, false, 80, IXR_Client::DEFAULT_USERAGENT, 'weblogUpdates.');
                    $res = $client->extendedPing(Helper::options()->title, Helper::options()->siteUrl, $obj->permalink, Helper::options()->feedUrl);
                    file_put_contents(dirname(__FILE__) . '/debug.log', sprintf('%s [cid:%s], [server:%s], [res:%s]', date('Y-m-d H:i:s'), $obj->cid, $server, var_export($res, true)) . "\n", FILE_APPEND);
                    unset($client);
                } catch (Exception $e) {
                    file_put_contents(dirname(__FILE__) . '/error.log', sprintf('%s [cid:%s], [server:%s], [code:%s], [msg:%s]', date('Y-m-d H:i:s'), $obj->cid, $server, $e->getCode(), $e->getMessage()) . "\n", FILE_APPEND);
                    continue;
                }
            }
        }
    }
}
