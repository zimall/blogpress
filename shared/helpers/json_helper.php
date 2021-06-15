<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Json_helper
	{
		private $last_error;
		private $last_error_msg;

		/**
		 * @return mixed
		 */
		public function get_last_error()
		{
			return $this->last_error;
		}

		/**
		 * @param mixed $last_error
		 * @return Json_helper
		 */
		public function set_last_error($last_error)
		{
			$this->last_error = $last_error;
			return $this;
		}

		/**
		 * @return mixed
		 */
		public function get_last_error_msg()
		{
			$errors = [
				JSON_ERROR_NONE => 'No error has occurred',
				JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
				JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
				JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
				JSON_ERROR_SYNTAX => 'Syntax error',
				JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
			];
			$i = $this->last_error;
			return isset($errors[$i]) ? $errors[$i] : 'Unknown error';
		}

		/**
		 * @param mixed $data
		 * @return array|string
		 */
		public function utf8_only($data){
			if(is_array($data)){
				foreach($data as $k=>$v){
					$data[$k] = $this->utf8_only($v);
				}
			}
			else{
				$data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
			}
			return $data;
		}

		/**
		 * @param mixed   $data
		 * @param int $options
		 * @return false|string
		 */
		public function encode($data, $options=0){
			$json = json_encode($data,$options);
			$e = json_last_error();
			if( $e == JSON_ERROR_UTF8 ){
				$data = $this->utf8_only($data);
				$json = json_encode($data,$options);
				$e = json_last_error();
			}
			$this->set_last_error($e);
			if($e == JSON_ERROR_NONE) return $json;
			return is_array($data) ? [] : '';
		}

		/**
		 * @param string $json
		 * @param bool   $assoc
		 * @param int    $depth
		 * @param int    $options
		 * @return false|mixed
		 */
		public function decode( $json , $assoc = false , $depth = 512 , $options = 0  ){
			$data = json_decode( $json, $assoc, $depth, $options );
			$e = json_last_error();
			$this->set_last_error($e);
			if($e == JSON_ERROR_NONE) return $data;
			return false;
		}
	}