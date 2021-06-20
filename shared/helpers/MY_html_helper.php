<?php 
/*This could be overkill but this strips all HTML tags and gives you 
the option to preserve the ones you define. It also takes into account 
tags like <script> removing all the javascript, too! You can also 
strip out all the content between any tag that has an opening and closing tag, like <table>, <object>, etc.
*/

    function remove_HTML($s , $keep = '' , $expand = 'script|style|noframes|select|option')
    {
        /**///prep the string
        $s = ' ' . $s;
        
        /**///initialize keep tag logic
        if(strlen($keep) > 0){
            $k = explode('|',$keep);
            for($i=0;$i<count($k);$i++){
                $s = str_replace('<' . $k[$i],'[{(' . $k[$i],$s);
                $s = str_replace('</' . $k[$i],'[{(/' . $k[$i],$s);
            }
        }
        
        //begin removal
        /**///remove comment blocks
        while(stripos($s,'<!--') > 0){
            $pos[1] = stripos($s,'<!--');
            $pos[2] = stripos($s,'-->', $pos[1]);
            $len[1] = $pos[2] - $pos[1] + 3;
            $x = substr($s,$pos[1],$len[1]);
            $s = str_replace($x,'',$s);
        }
        
        /**///remove tags with content between them
        if(strlen($expand) > 0){
            $e = explode('|',$expand);
            for($i=0;$i<count($e);$i++){
                while(stripos($s,'<' . $e[$i]) > 0){
                    $len[1] = strlen('<' . $e[$i]);
                    $pos[1] = stripos($s,'<' . $e[$i]);
                    $pos[2] = stripos($s,$e[$i] . '>', $pos[1] + $len[1]);
                    $len[2] = $pos[2] - $pos[1] + $len[1];
                    $x = substr($s,$pos[1],$len[2]);
                    $s = str_replace($x,'',$s);
                }
            }
        }
        
        /**///remove remaining tags
        while(stripos($s,'<') > 0){
            $pos[1] = stripos($s,'<');
            $pos[2] = stripos($s,'>', $pos[1]);
            $len[1] = $pos[2] - $pos[1] + 1;
            $x = substr($s,$pos[1],$len[1]);
            $s = str_replace($x,'',$s);
        }
        
        /**///finalize keep tag
        for($i=0;$i<count($k);$i++){
            $s = str_replace('[{(' . $k[$i],'<' . $k[$i],$s);
            $s = str_replace('[{(/' . $k[$i],'</' . $k[$i],$s);
        }
        
        return trim($s);
}


	function strip_only($str, $tags, $stripContent = TRUE)
	{
		$content = '';
		if(!is_array($tags))
		{
			$tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
			if(end($tags) == '') array_pop($tags);
		}
		foreach($tags as $tag)
		{
			if ($stripContent)
				$content = '(.+</'.$tag.'[^>]*>|)';
			$str = preg_replace('#</?'.$tag.'[^>]*>'.$content.'#is', '', $str);
		}
		return $str;
	}

	if(!function_exists('get_image')){
		function get_image($name, $size='md', $default='noimage.svg', $path='images/articles'){
			$img = $size? "{$path}/{$size}/{$name}" : "{$path}/{$name}";
			$f = ( file_exists($img) && is_file($img) );
			if(!$f){
				$img = $webp = "images/{$default}";
				//$webp = 'images/placeholder.webp?ext=jpg';
			}
			else{
				$ext = pathinfo($img, PATHINFO_EXTENSION);
				$webp = str_replace( ".{$ext}", ".webp?ext={$ext}", $img );
			}
			$bg = "background-image: url(".base_url($webp)."), url(".base_url($img).")";
			return [ 'src'=>$img, 'webp'=>$webp, 'bg'=>$bg, 'exists'=>$f ];
		}
	}
