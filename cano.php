<?php 
class cano
{
    public $urls=[];
    public $notUrls=[];
    public $externalUrls=[];
    public $urlsMap=[];

    public $attr404=[];
    public $host="";
    public $firstUrl="";
    
    public function __construct($defines){
        $firstUrl=parse_url($defines["url"]);
        $this->attr404=$defines["attr"];
        $this->firstUrl=$defines["url"];
        $this->host=isset($defines["host"]) && !empty($defines["host"])?$defines["host"]:$firstUrl["host"];
        $this->urls[$this->firstUrl]="first Url";
         $this->urlsMap[$this->firstUrl]=["parent"=>0,"name"=>"first Url"];
    }
    public function visit($url='',$name=""){
        $url=$url==''?$this->firstUrl:$url;
        $urlControl=$this->url_exist($url);
        if($urlControl===0){
            // die();
        }
        else{ 
            $content= $urlControl;
            preg_match_all('/<a.*href=\"([^\"]*)\".*>\s*(.*)\s*<\/a>/mi',$content,$match);
            //print_r($match);
            if(!empty($match[1])){
                foreach($match[1] as $idx=>$link)
                {    
                    $parse = parse_url($link);
                    
                    if(empty($parse)){
                        $parse["path"]=$link;
                    }

                    if((!isset($parse['path'])) && isset($parse["fragment"])){
                        continue;
                    }
                    
                    if(!isset($parse['host'])){
                        $urlParse=parse_url($url);
                        $link=$urlParse["scheme"]."://".$urlParse["host"];
                        $link.=$parse['path'][0]=="/"?$parse['path']:"/".$parse['path'];
                        $parse = parse_url($link);
                    }
                    
                    if($parse['host']==$this->host || $parse['host']=="www.".$this->host){
                        if(!isset($this->urls[$link])){  
                            $this->urls[$link]=$match[2][$idx];
                            $this->urlsMap[$link]=["parent"=>$url,"name"=>$match[2][$idx]];
                            $this->visit($link,$match[2][$idx]);
                        }
                    }
                    else{
                        $this->externalUrls[$link]=$match[2][$idx];
                    }
                }
            }
        }
    }
    public function url_exist($url,$name="")
    {
        $response_code = 200;
        $curl = curl_init();
        curl_setopt_array( $curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION=>true,
            CURLOPT_URL => $url, 
            CURLOPT_ENCODING=>  ''
        ));
        $data=curl_exec( $curl );
        $header_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
        curl_close( $curl );
        
        if ($header_code == 301 || $header_code==200 ){
            return preg_match('/'.$this->attr404[0].'="(.*)'.preg_quote($this->attr404[1]).'(.*)"/s',$data)?0:$data;
        }
        else{
            $this->notUrls[$url]=$name;
            return 0;
        }
    }
    public function findChildren($list=array(),$parent=0){
		$items = array();
		foreach ($list as $id=>$item) {
			if ($item['parent'] === $parent){
				$item['children'] = $this->findChildren($list, $id);
                $item["url"]=$id;
				$items[] = $item;
			}
		}
		
		return $items;
	}
}
