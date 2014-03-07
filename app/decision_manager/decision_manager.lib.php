<?php

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
        
        return $schelarship_percentage;
    }
?>