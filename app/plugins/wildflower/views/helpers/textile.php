<?php
class TextileHelper extends AppHelper {
    
    function format($string) {
        App::import('Vendor', 'classTextile', array('file' => 'classTextile.php'));
        $textile = new Textile();
        return $textile->TextileThis($string);
    }
    
    function htmlToTextile($text) {
        return self::detextile($text);
    }
    
    // The following functions are used to detextile html, a process
    // still in development.
    // By Tim Koschützki
    // Based on code from http://www.aquarionics.com
    function detextile($text) {

        $text = preg_replace("/<br \/>\s*/","\n",$text);

        $oktags = array('p','ol','ul','li','i','b','em','strong','span','a','h[1-6]',
          'table','tr','td','u','del','sup','sub','blockquote');

        foreach($oktags as $tag){
          $text = preg_replace_callback("/\t*< (".$tag.")\s*([^>]*)>(.*)< \/\\1>/Usi",
          array($this,'processTag'),$text);
        }

        $text = $this->detextile_process_glyphs($text);
        $text = $this->detextile_process_lists($text);

            $text = preg_replace('/^\t* *p\. /m','',$text);

            return $this->decode_high($text);
        }

      function detextile_process_glyphs($text) {
        $glyphs = array(  
          '&#8217;'=>'\'',        # single closing
          '&#8216;'=>'\'',        # single opening
          '&#8221;'=>'"',         # double closing
          '&#8220;'=>'"',         # double opening
          '&#8212;'=>'--',        # em dash
          '&#8211;'=>' - ',       # en dash
          '&#215;' =>'x',         # dimension sign
          '&#8482;'=>'(TM)',      # trademark
          '&#174;' =>'(R)',       # registered
          '&#169;' =>'(C)',       # copyright
          '&#8230;'=>'...'        # ellipsis
        );

        foreach($glyphs as $f=>$r){
          $text = str_replace($f,$r,$text);
        }
        return $text;
      }

      function detextile_process_lists($text) {
        $list = false;

        $text = preg_split("/(< .*>)/U",$text,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($text as $line){

          if ($list == false && preg_match('/<ol /',$line)){
            $line = "";
            $list = "o";
          } else if (preg_match('/<\/ol/',$line)){
            $line = "";
            $list = false;
          } else if ($list == false && preg_match('/<ul/',$line)){
            $line = "";
            $list = "u";
          } else if (preg_match('/<\/ul/',$line)){
            $line = "";
            $list = false;
          } else if ($list == 'o'){
            $line = preg_replace('/<li.*>/U','# ', $line);
          } else if ($list == 'u'){
            $line = preg_replace('/<li .*>/U','* ', $line);
          }
          $glyph_out[] = $line;
        }

        return $text = implode('',$glyph_out);
      }

      function processTag($matches) {
            list($all,$tag,$atts,$content) = $matches;
        $a = $this->splat($atts);

        $phr = array(
        'em'=>'_',
        'i'=>'__',
        'b'=>'**',
        'strong'=>'*',
        'cite'=>'??',
        'del'=>'-',
        'ins'=>'+',
        'sup'=>'^',
        'sub'=>'~',
        'span'=>'%');

        $blk = array('p','h1','h2','h3','h4','h5','h6');

        if(isset($phr[$tag])) {
            return $phr[$tag].$this->sci($a).$content.$phr[$tag];
        } elseif($tag=='blockquote') {
            return 'bq.'.$this->sci($a).' '.$content;
        } elseif(in_array($tag,$blk)) {
            return $tag.$this->sci($a).'. '.$content;
        } elseif ($tag=='a') {
            $t = $this->filterAtts($a,array('href','title'));
            $out = '"'.$content;
            $out.= (isset($t['title'])) ? ' ('.$t['title'].')' : '';
            $out.= '":'.$t['href'];
            return $out;
        } else {
            return $all;
        }
    }


    function filterAtts($atts,$ok) 
    {
        foreach($atts as $a) {
            if(in_array($a['name'],$ok)) {
                if($a['att']!='') {
                $out[$a['name']] = $a['att'];
                }
            }
        }
#        dump($out);
        return $out;
    }


    function sci($a) 
    {
        $out = '';
        foreach($a as $t){
            $out.= ($t['name']=='class') ? '(='.$t['att'].')' : '';
            $out.= ($t['name']=='id') ? '[='.$t['att'].']' : '';
            $out.= ($t['name']=='style') ? '{='.$t['att'].'}' : '';
            $out.= ($t['name']=='cite') ? ':'.$t['att'] : '';
        }
        return $out;
    }


    function splat($attr)  // returns attributes as an array
    {
        $arr = array();
        $atnm = '';
        $mode = 0;

        while (strlen($attr) != 0){
            $ok = 0;
            switch ($mode) {
                case 0: // name
                    if (preg_match('/^([a-z]+)/i', $attr, $match)) {
                        $atnm = $match[1]; $ok = $mode = 1;
                        $attr = preg_replace('/^[a-z]+/i', '', $attr);
                    }
                break;

                case 1: // =
                    if (preg_match('/^\s*=\s*/', $attr)) {
                        $ok = 1; $mode = 2;
                        $attr = preg_replace('/^\s*=\s*/', '', $attr);
                    break;
                    }
                    if (preg_match('/^\s+/', $attr)) {
                        $ok = 1; $mode = 0;
                        $arr[] = array('name'=>$atnm,'whole'=>$atnm,'att'=>$atnm);
                        $attr = preg_replace('/^\s+/', '', $attr);
                    }
                break;

                case 2: // value
                    if (preg_match('/^("[^"]*")(\s+|$)/', $attr, $match)) {
                        $arr[]=array('name' =>$atnm,'whole'=>$atnm.'='.$match[1],
                                'att'=>str_replace('"','',$match[1]));
                        $ok = 1; $mode = 0;
                        $attr = preg_replace('/^"[^"]*"(\s+|$)/', '', $attr);
                    break;
                    }
                    if (preg_match("/^('[^']*')(\s+|$)/", $attr, $match)) {
                        $arr[]=array('name' =>$atnm,'whole'=>$atnm.'='.$match[1],
                                'att'=>str_replace("'",'',$match[1]));
                        $ok = 1; $mode = 0;
                        $attr = preg_replace("/^'[^']*'(\s+|$)/", '', $attr);
                    break;
                    }
                    if (preg_match("/^(\w+)(\s+|$)/", $attr, $match)) {
                        $arr[]=
                            array('name'=>$atnm,'whole'=>$atnm.'="'.$match[1].'"',
                                'att'=>$match[1]);
                        $ok = 1; $mode = 0;
                        $attr = preg_replace("/^\w+(\s+|$)/", '', $attr);
                    }
                break;
            }
            if ($ok == 0){
                $attr = preg_replace('/^\S*\s*/', '', $attr);
                $mode = 0;
            }
        }
        if ($mode == 1) $arr[] = 
                array ('name'=>$atnm,'whole'=>$atnm.'="'.$atnm.'"','att'=>$atnm);

        return $arr;
    }
    
}
