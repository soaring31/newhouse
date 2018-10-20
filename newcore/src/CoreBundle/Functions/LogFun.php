<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-4-03
*/

namespace CoreBundle\Functions;

/**
 * 日志服务
 *
 */
class LogFun {

	protected $log;
	protected $config;
	protected $file;
	protected $line;

	public function __construct( $config)
	{
		$this->config = $config;
	}

	public function exception_path($file, $line)
	{
		$this->file = $file;
		$this->line = $line;
		return $this;
	}

	public function writeLog( $log)
	{
		if( FALSE == file_exists( $this->config['log_path']))
		{
			file_put_contents( $this->config['log_path'] , '');
		}

		$this->log = "[".date('Y-m-d h:i:s')."] - ".$this->file.":line ".$this->line." Exception : " .$log. "\r\n";
		return $this;
	}

	public function flush()
	{
		file_put_contents( $this->config['log_path'] , $this->log  , FILE_APPEND );
		return $this;
	}

	public function readLog()
	{
		$log = file_get_contents( $this->config['log_path']);

		if( ! $log)
			return FALSE;

		$log = explode("\r\n" , $log);
		$log = implode('<br/>' , $log);

		return $log;
	}
}