<?php

    function makePDF($screen)
    {
        ob_start();
        $dompdf = new DOMPDF();
        
        $dompdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'landscape');
        $dompdf->load_html($screen);
        $dompdf->render();
        //$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));    
        $filename     = 'report.pdf';
        $output       = $dompdf->output();
        $file_to_save = $_SERVER['DOCUMENT_ROOT'].'/files/'.$filename;
        
        file_put_contents($file_to_save, $output);
        
        header("HTTP/1.1 200 OK");
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment;filename="' . $filename. '"');
        header('Content-Type: text/plain; charset=utf-8');
        header("Content-Transfer-Encoding: binary");
        header ('Location: /files/'.$filename);
    }

    function currentSessionAmount($sid)
    {
        $info['table']  = SESSIONS_TBL;
        $info['debug']  = false;
        $info['fields'] = array('scholarship_bulk_amount');
        $info['where']  = 'id = ' . $sid;
        
        $result = select($info);
        
        return $result[0]->scholarship_bulk_amount;
    }
    
    function getTotalTicketFare($sid)
    {
        $info['table']  = APPLICATIONS_TBL.' AS AT LEFT JOIN ' . TICKETS_TBL . ' AS TT ON (AT.uid=TT.uid)'; 
        $info['debug']  = false;
        $info['fields'] = array('SUM(TT.total) AS total_ticket_fare');
        $info['where']  = 'AT.application_status = ' . q('Accepted') . '  ORDER BY AT.country';

        $result = select($info);
        
        return $result[0]->total_ticket_fare;
    }
    
    function getScheloarshipPercentage($scholarship_bulk_amount, $total_ticket_fare)
    {
        if ($scholarship_bulk_amount / $total_ticket_fare > MAX_THRESHOLD)
        {
            $schelarship_percentage = MAX_THRESHOLD;
        }
        else if ($scholarship_bulk_amount / $total_ticket_fare < MAX_THRESHOLD)
        {
            $schelarship_percentage = $scholarship_bulk_amount / $total_ticket_fare;
        }
        
//        echo_br('scholarship_bulk_amount = ' . $scholarship_bulk_amount);
//        echo_br('total_ticket_fare = ' . $total_ticket_fare);
//        echo_br('MAX THRESHOLD = ' . MAX_THRESHOLD);
//        echo_br('MAX schelarship_percentage = ' . $schelarship_percentage);
        
        
        return $schelarship_percentage;
    }
?>