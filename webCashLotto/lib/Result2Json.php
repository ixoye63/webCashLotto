<?php

/**
 * ResultJson - DB Result Set를 Json 구조로 변경.
 */
class Result2Json
{
	protected $Version = '0.1';
	public $Json_Data;
	
	public function __construct($exceptions = false)
    {
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
    }
	
	public function addHead($request, $total_count = 0, $query_state, $msg)
	{
		
		$this->Json_Data['request'] = $request;
		$this->Json_Data['version'] = "cashlotto v.1.0";
		// $this->Json_Data['totalcount'] = $total_count;
		$this->Json_Data['querystate'] = urlencode($query_state);
		$this->Json_Data['msg'] = urlencode($msg);
	}

	public function addExecutionTime($start_mtime)
	{
		$end_mtime = microtime(true);
			
		$this->Json_Data['start_mtime,'] = convertMicrotimeToDate($start_mtime);		
		$this->Json_Data['end_mtime'] = convertMicrotimeToDate($end_mtime);
		$this->Json_Data['exe_mtime'] = sprintf("%.6f", $end_mtime - $start_mtime);		
	}


	protected function result2Assoc($result) 
	{
		$i=0;
		$ret = array();
		while ($row = mysql_fetch_assoc($result)) {
			foreach ($row as $key => $value) 
			{
				$ret[$i][$key] = urlencode($value);
			}
			$i++;
      	}
		return ($ret);
	}
	
	public function addResult($result_name, $result_data)
	{
		$assocarray =  $this->result2Assoc($result_data);	
		$this->Json_Data[$result_name] = $assocarray;		
	}
	
	public function addJson($result_name, $json_data)
	{
		$this->Json_Data[$result_name] = $json_data;		
	}
	
	public function getJson()
	{
		return urldecode(json_encode($this->Json_Data));	
	}
	
		
}