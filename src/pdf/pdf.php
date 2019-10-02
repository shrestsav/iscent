<?php
    $footer = '<page_footer>
        <table class="page_footer" style=" font-size:11px;width:100%;" width="100%">
            <tr>
                <td  style="width:30%;padding-left:20px;">
                   '.$_SERVER['HTTP_HOST'].'
                   <div style="width:120px;height:2px;"></div>
                </td>
                
                <td   style="width:30%;text-align: center"> page [[page_cu]]/[[page_nb]]
                <div style="width:410px;height:2px;"></div>
                </td>
                <td  align="right" style="width:25%;padding-right:00px;text-align:right;">
                  <div style="float:right">'.date('d-m-Y').'</div>
		    
                </td>
            </tr>
        </table>
    </page_footer>';

    $content = '';
    if(isset($_POST['pdfFooter']) && $_POST['pdfFooter']==true){
        $content = $footer;
    }

    //ob_get_clean();
    @$content .= "$_POST[pdfContent]";

    include(__DIR__."/html2pdf.class.php");
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content, isset($_GET['debug']));
        $html2pdf->Output('IBMS-Pdf.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

?>