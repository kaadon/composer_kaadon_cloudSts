<?php
/**
 *   +----------------------------------------------------------------------
 *   | PROJECT:   [ composer_kaadon_cloudsts ]
 *   +----------------------------------------------------------------------
 *   | 官方网站:   [ https://developer.kaadon.com ]
 *   +----------------------------------------------------------------------
 *   | Author:    [ kaadon.com <kaadon.com@gmail.com>]
 *   +----------------------------------------------------------------------
 *   | Tool:      [ PhpStorm ]
 *   +----------------------------------------------------------------------
 *   | Date:      [ 2024/9/2 ]
 *   +----------------------------------------------------------------------
 *   | 版权所有    [ 2020~2024 kaadon.com ]
 *   +----------------------------------------------------------------------
 **/

namespace Kaadon\CloudSts;

use AlibabaCloud\SDK\Sts\V20150401\Models\AssumeRoleRequest;
use AlibabaCloud\SDK\Sts\V20150401\Sts;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Darabonba\OpenApi\Models\Config;

class OssSts
{
    private $config = [];

    /**
     * @throws \Exception
     */
    public function __construct($config)
    {
        $this->config = array_merge($this->config, $config);
        if (!isset($this->config['accessKeyId']) || !isset($this->config['accessKeySecret'])) {
            throw new \Exception('accessKeyId or accessKeySecret is required');
        }
    }

    /**
     * @return array
     */
    public function getSts(): array
    {
        // 运行本代码示例之前，请确保已使用步骤1创建的RAM用户的访问密钥设置环境变量YOUR_ACCESS_KEY_ID和YOUR_ACCESS_KEY_SECRET。
        $config = new Config([
            'accessKeyId' => $this->config['accessKeyId'],
            'accessKeySecret' => $this->config['accessKeySecret'],
        ]);
        //
        $config->endpoint = "sts.cn-hangzhou.aliyuncs.com";
        $client = new Sts($config);

        $assumeRoleRequest = new AssumeRoleRequest([
            "roleArn" => "acs:ram::1578925264248926:role/ossstsrole",
            "roleSessionName" => "sessiontest",
            "durationSeconds" => 3000,
        ]);
        $runtime = new RuntimeOptions([]);
        $result = $client->assumeRoleWithOptions($assumeRoleRequest, $runtime);
        return $result->body->credentials->toMap();
    }
}

