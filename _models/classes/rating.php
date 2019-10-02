<?php

class rating extends  object_class{

    private $firstViewCall = false;
    function __construct()
    {
        parent::__construct('3');

        $_w['{{average}} From {{total}} Votes'] = '';
        $_e = $this->dbF->hardWordsMulti($_w,currentWebLanguage(),'Web Rating');

    }

    public function addRatings_(){
        $data['rate']   = !empty($_POST['rate']) ? $_POST['rate'] : 0;
        $data['type']   = !empty($_POST['type']) ? $_POST['type'] : "like";
        $pId            = !empty($_POST['pId'])  ? $_POST['pId']  : 0;

        if(empty($pId)){
            echo "0";
            return false;
        }
        $data['p_id']   = $pId;
        $data['ip']     = $this->functions->userIp();

        if(userLoginCheck()){
            $data['user_id'] = webUserId();
        }else{
            $data['user_id'] = webTempUserId();
        }

        //check if not already insert
        $sql        =  "SELECT id FROM rating WHERE (user_id = '$data[user_id]' OR ip='$data[ip]') AND p_id = '$data[p_id]' AND type='$data[type]'";
        $dataOld    =  $this->dbF->getRow($sql);
        if(empty($dataOld)){
            $lastId = $this->functions->formInsert("rating",$data);
        }else{
            //already found,, update rating..
            $id = $dataOld['id'];
            $lastId = $this->functions->formUpdate("rating",$data,$id);
        }



        if($lastId){
            $info =  $this->ratingInfo($data['p_id'],$data['type']);
            echo json_encode($info);
        }else {
            echo "0";
        }
    }

    public function ratingInfo($id,$type){
        $sql    =   "SELECT count(id) as cnt,avg(rate) as rate FROM rating WHERE p_id='$id' AND type='$type'";
        $data   =   $this->dbF->getRow($sql);
        $avg    =   round(floatval($data['rate']),1);
        $full   =   floor($avg);
        $noOfVotes = $data['cnt'];

        return array("avg"=>$avg,
                    "noOfVotes"=>$noOfVotes
        );
    }

    public function ratingView($id,$type,$msg=''){
        global $_e;
        $sql    =   "SELECT count(id) as cnt,avg(rate) as rate FROM rating WHERE p_id='$id' AND type='$type'";
        $data   =   $this->dbF->getRow($sql);
        $avg    =   round(floatval($data['rate']),1);
        $full   =   floor($avg);
        $ceil   =   ceil($avg);
        $noOfVotes = $data['cnt'];
        $ratings = "<div class='rating_cover_stars'>";

        for($i=1;$i<=5;$i++){
            $class = "";
            if($full>=$i || $avg >= ($i-0.2)){
                $class = "ratings_full";
            }else if( $avg > $i-0.8){
                $class = "ratings_half";
            }

            $ratings    .=   "<div class='star_$i $class  ratings_stars' data-val='$i'></div>";

        }
        $ratings .= "</div>";

        $script = '';
        if($this->firstViewCall==false)
            $script = $this->script();

        $this->firstViewCall = true;

        if(!empty($msg)){
            $msg = "<div class='rate_heading'>$msg</div>";
        }

        $votes = _replace("{{average}}","<span class='rating_avg'>$avg</span>",$_e['{{average}} From {{total}} Votes']);
        $votes = _replace("{{total}}","<span class='rating_noOfVotes'>$noOfVotes</span>",$votes);

        return "<div class='rate_widget rating_type_$type' data-type='$type' data-pId='$id'>
                    $msg
                    $ratings
                    <div class='total_votes'>$votes</div>
              </div>
              $script
              ";
    }


    private function script(){
        return "
                <script>
                    $(document).ready(function(){
                        $('.ratings_stars').hover(
                            // Handles the mouseover
                            function() {
                                $(this).prevAll().andSelf().addClass('ratings_over');
                                $(this).nextAll().addClass('ratings_vote');
                            },
                            // Handles the mouseout
                            function() {
                                $(this).prevAll().andSelf().removeClass('ratings_over');
                                $(this).nextAll().andSelf().removeClass('ratings_vote');
                            }
                        );
                        $('.ratings_stars').click(function(){
                            setRatingVote(this);
                        });

                    });

                    function setRatingVote(widget) {
                        var vote =  $(widget).attr('data-val');
                        var type =  $(widget).closest('.rate_widget').attr('data-type');
                        var pId  =  $(widget).closest('.rate_widget').attr('data-pId');
                        //active user no rates
                        $(widget).prevAll().andSelf().addClass('ratings_user').removeClass('ratings_half');
                        $(widget).nextAll().removeClass('ratings_user').removeClass('ratings_full').removeClass('ratings_half');

                        $.ajax({
                            type: 'POST',
                            url : '".WEB_URL."/ajax_call.php?page=addRating',
                            data: { rate:vote,pId:pId,type:type}
                        }).done(function(data){
                            if(data=='0'){

                            }else{
                                obj         =  jQuery.parseJSON( data );
                                avg         = obj.avg;
                                noOfVotes   = obj.noOfVotes;
                                $(widget).closest('.rate_widget').find('.rating_avg').text(avg);
                                $(widget).closest('.rate_widget').find('.rating_noOfVotes').text(noOfVotes);
                            }
                        });
                    }
                </script>
";

    }

}





















