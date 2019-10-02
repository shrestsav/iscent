<?php

class web_news extends  object_class
{
    public $webClass;
    function __construct()
    {
        parent::__construct('3');
        $this->webClass =   $GLOBALS['webClass'];

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        $_w=array();
        $_w['Read More..'] = '';
        $_w['Show All News'] = '';
        $_e    =   $this->dbF->hardWordsMulti($_w,currentWebLanguage(),'Website News');
    }


    public function newsSlide(){
        global $_e;
        $today  = date('Y-m-d');
        $sql    = "SELECT * FROM news WHERE publish = '1' AND publish_date <= ? ORDER BY `date` ";
        $data   =  $this->dbF->getRows($sql, array($today));

        $temp = '<ul id="news">';
        foreach($data as $key=>$val){
            $date       = date('d/m/Y',strtotime($val['date']));
            $heading    = translateFromSerialize($val['heading']);
            $shortDesc  = translateFromSerialize($val['shortDesc']);
            $dsc        = translateFromSerialize($val['dsc']);
            $imageR     = ($val['image']);
            $comment    = $val['comment'];
            $publishDate    = date('d-m-Y',strtotime($val['publish_date']));
            $updateTime     = date('d-m-Y',strtotime($val['dateTime']));
            $id         = $val['id'];
            $link       =   WEB_URL."/news?n=$id&title=$heading";

            $imageR     = $this->functions->resizeImage($val['image'],'290','175',false);

            $shortDesc = strip_tags($shortDesc);


            //Print Your View Here
            $temp .= "<li>
                    <div class='news'>
                        <div class='hiddenOverflow'>
                            <span>
                                $shortDesc <br>
                            </span>
                        </div>
                        <div class='dateNews'>
                            <span style='color:#ed2024; font-weight:bold'>$date</span>
                        </div>
                        <div class='readMoreNews'>
                            <a href='$link'>".$_e['Read More..'] ."</a>
                        </div>
                    </div>
                </li>";

        }

        $temp .= '</ul>';
        return $temp;
    }


    public function newsCollapse(){
        global $_e;
        $today  = date('Y-m-d');
            $sql    = "SELECT * FROM news WHERE publish = '1' AND publish_date <= ? ORDER BY `date` ";
            $data   =  $this->dbF->getRows($sql,array($today));

        $temp = '<script>
                    $(function() {
                        $( "#accordion" ).accordion({
                            heightStyle: "content"
                        });
                    });
                </script>
            <div id="accordion" class="container-fluid  ">';

        foreach($data as $key=>$val){
            $date       = date('d/m/Y',strtotime($val['date']));
            $heading    = translateFromSerialize($val['heading']);
            $shortDesc  = translateFromSerialize($val['shortDesc']);
            $dsc        = translateFromSerialize($val['dsc']);
            $imageR     = $val['image'];
            $image2     = WEB_URL."/images/".$imageR;
            $comment    = $val['comment'];
            $publishDate    = date('d-m-Y',strtotime($val['publish_date']));
            $updateTime     = date('d-m-Y',strtotime($val['dateTime']));
            $id         = $val['id'];
            $link       =   WEB_URL."/news?n=$id&title=$heading";

            $shortDesc = strip_tags($shortDesc);
            $imageDiv = "";
            if(!empty($imageR)) {
                $imageDiv = "<div class='text-center col-xs-12 '>
                            <img src='$image2' class='img-responsive'/>
                        </div>";
            }

            //Print Your View Here
            $temp .= "<h3>$heading <small>($date)</small></h3>
                    <div class='newCollapse'>
                        $imageDiv
                        <p>$shortDesc</p>
                        <a href='$link' class='btn themeButton'>
                            ".$_e['Read More..'] ."
                        </a>
                    </div>
                    ";

        }

        $temp .= '</ul>';
        return $temp;
    }


    public function newsDetail($id){
        global $_e;
        $today  = date('Y-m-d');
        $sql    = "SELECT * FROM news WHERE publish = '1' AND publish_date <= ? AND id = ? ORDER BY `date` ";
        $data   =  $this->dbF->getRows($sql,array($today,$id));

        $temp = '<div id="accordion">';
        foreach($data as $key=>$val){
            $date       = date('d/m/Y',strtotime($val['date']));
            $heading    = translateFromSerialize($val['heading']);
            $shortDesc  = translateFromSerialize($val['shortDesc']);
            $dsc        = translateFromSerialize(base64_decode($val['dsc']));
            $imageR     = $val['image'];
            $image2     = WEB_URL."/images/".$imageR;
            $comment    = $val['comment'];
            $publishDate    = date('d-m-Y',strtotime($val['publish_date']));
            $updateTime     = date('d-m-Y',strtotime($val['dateTime']));
            $id         = $val['id'];
            $link       =   WEB_URL."/news?n=$id&title=$heading";

            $imageDiv = "";
            if(!empty($imageR)) {
                $imageDiv = "<div class='text-center col-xs-12 '>
                            <img src='$image2' class='img-responsive'/>
                        </div>";
            }

            $reviewMsg = "";
            if(@$comment=='1'){
                $this->functions->require_once_custom('webBlog_functions');
                $blogC = new webBlog_functions();
                $reviewMsg = $blogC->reviewSubmit();
                $reviews =  $blogC->reviews($id,'page',2);
                $reviews = '<div class="pageReview container-fluid padding-0 table-bordered">'.$reviews.'</div>';
            }else{
                $reviews = '';
            }

            //Print Your View Here
            $temp .= "
                    $reviewMsg

                    <div class='h2'>$heading <small>($date)</small></div>
                    <div>
                        $imageDiv
                        <p>$shortDesc</p>
                        <hr>
                        <p>$dsc</p>

                        $reviews

                    </div>
                    ";

        }

        $temp .= '</ul>';
        return $temp;
    }


    public function newsAll(){

    }

}

?>
