<?php
/**
 * User: paolo
 * Date: 2016/6/22
 */
namespace \Lib\Util;

use Aws\S3\S3Client;

class S3Util {
    private $bucket = '';
    private $config = [];
    
    public function __construct()
    {
        $structConf = \Lib\Util\Config::loadConfig('struct');
        $s3Conf = $structConf['s3_client'];
        
        $this->bucket = $s3Conf['bucket'];
        $credentials = new \Aws\Credentials\Credentials($s3Conf['key'], $s3Conf['secret']);
        $this->config = [
            'version'     => 'latest',
            'region'      => 'us-east-1',
            'credentials' => $credentials
        ];
    }
    
    /*
     * 上传文件
     */
    public function uploadFile($sourceFile, $targetFile)
    {
        $s3 = new S3Client($this->config);
        return $s3->putObject([
            'Bucket'     => $this->bucket,
            'Key'        => $targetFile,
            'SourceFile' => $sourceFile,
            'ACL'        => 'public-read'
        ]);
    }
    
    /*
     * 上传目录
     */
    public function uploadDir($fromDir)
    {
        $s3 = new S3Client($this->config);
        return $s3->uploadDirectory($fromDir, $this->bucket, '/assets/');
    }
    
    /*
     * 删除单个文件
     */
    public function deleteFile($key)
    {
        $s3 = new S3Client($this->config);
        return $s3->deleteObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
        ]);
    }
    
    /*
     * 删除多个文件
     */
    public function deleteFiles($keys)
    {
        $s3 = new S3Client($this->config);
        return $s3->deleteObjects([
            'Bucket'  => $this->bucket,
            'Objects' => array_map(function ($key) {
                return ['Key' => $key];
            }, $keys)
        ]);
    }
}