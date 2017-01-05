<?php 
class cano
{
    public $urls=[];
    public $notUrls=[];
    public $externalUrls=[];

    public $attr404=[];
    public $host="";
    public $firstUrl="";
    
    public function __construct($defines){
        $firstUrl=parse_url($defines["url"]);
        $this->attr404=$defines["attr"];
        $this->firstUrl=$defines["url"];
        $this->host=isset($defines["host"]) && !empty($defines["host"])?$defines["host"]:$firstUrl["host"];
        $this->urls[$this->firstUrl]="first Url";
    }
    public function visit($url='',$name=""){
        $url=$url==''?$this->firstUrl:$url;
        $urlControl=$this->url_exist($url);
        if($urlControl){
            
            $content = file_get_contents($url);
            //echo $content;
            preg_match_all('/<a.*href=\"([^\"]*)\".*>\s*(.*)\s*<\/a>/mi',$content,$match);
            //print_r($match);
            if(!empty($match[1])){
                foreach($match[1] as $idx=>$link){  
                  
                    $parse = parse_url($link);
                    if(empty($parse)){
                        $parse["path"]=$link;
                    }
                    if(!isset($parse['host'])){
                        $urlParse=parse_url($url);
                        $link=$urlParse["scheme"]."://".$urlParse["host"];
                        $link.=$parse['path'][0]=="/"?$parse['path']:"/".$parse['path'];
                        $parse = parse_url($link);
                    }
                    
                    if($parse['host']==$this->host)
                    {
                        if(!isset($this->urls[$link]))
                        {
                            $urlControl=$this->url_exist($link,$match[2][$idx]);
                            if($urlControl)
                            {
                                $this->urls[$link]=$match[2][$idx];
                                $this->visit($link,$match[2][$idx]);
                            }
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
            CURLOPT_URL => $url 
        ));
        $data=curl_exec( $curl );
        $header_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
        curl_close( $curl );
        
        if ($header_code == 301 || $header_code==200 )
        {
            return preg_match('/'.$this->attr404[0].'="(.*)'.preg_quote($this->attr404[1]).'(.*)"/s',$data)?0:1;
        }
        else
        {
            $this->notUrls[$url]=$name;
            return 0;
        }
    }
}
