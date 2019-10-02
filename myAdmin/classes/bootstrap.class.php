<?php
class bootstrap_model
{

    public $title;
    public $body;
    public $id;
    public $formAction;
    public $formMethod;
    public $formOkBtnText;
    public $formCloseBtnText;
    public $btnText;
    public $btnClass;
    public $btnExtraTags;
    public $extraAttr;

    function __construct($type = "form")
    {

        $this->btnText = "@btnText";
        $this->btnClass = "btn btn-default btn-xs";
        $this->formCloseBtnText = "Close";
        $this->formOkBtnText = "Submit";
        $this->formMethod = "post";
        $this->extraAttr = "";

        switch ($type):
            case "form":
                $this->setDefaultFormVariables();
                break;

            default:
                $this->setDefaultFormVariables();
                break;
        endswitch;

    }

    private function setDefaultFormVariables()
    {
        $this->title = "Model Title @title";
        $this->id = uniqid("bsmodel-");
        $this->body = "@body";
        $this->formAction = "";
    }

    public function getBtn()
    {
        return <<<htm
        <button type='button' data-toggle="modal" href="#$this->id" class="$this->btnClass" $this->btnExtraTags >
            $this->btnText
        </button>
htm;

    }

    public function getModel()
    {

        return <<<htm
<div class="modal fade" id="$this->id" tabindex="-1" role="dialog" aria-labelledby="ColorEditModalLabel"
     aria-hidden="true" $this->extraAttr >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">$this->title</h4>
            </div>

            <form method="$this->formMethod" action="$this->formAction">
                <div class="modal-body">
                    $this->body
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">$this->formCloseBtnText</button>
                    <button type="submit" class="btn btn-primary">$this->formOkBtnText</button>
                </div>
            </form>

        </div>
    </div>
</div>
htm;
    }

}


class bs_alert
{

    private static function alerts($type, $text, $echo = true)
    {
        if ($echo) echo "<div class='alert alert-$type'>$text <a class='close' data-dismiss='alert' href='#' aria-hidden='true'>&times;</a></div>";
        else return "<div class='alert alert-$type'>$text <a class='close' data-dismiss='alert' href='#' aria-hidden='true'>&times;</a></div>";
    }


    public static function success($text, $echo = true)
    {
        self::alerts("success", $text, $echo);
    }

    public static function info($text, $echo = true)
    {
        return self::alerts("info", $text, $echo);
    }

    public static function warning($text, $echo = true)
    {
        return self::alerts("warning", $text, $echo);
    }

    public static function danger($text, $echo = true)
    {
        return self::alerts("danger", $text, $echo);
    }
}

?>