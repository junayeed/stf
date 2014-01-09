<?php

    function currentSessionAmount($sid)
    {
        $info['table']  = SESSIONS_TBL;
        $info['debug']  = false;
        $info['fields'] = array('scholarship_bulk_amount ');
        $info['where']  = 'id = ' . $sid;
        
        $result = select($info);
        
        return $result[0]->scholarship_bulk_amount;
    }
    
    function getTotalTicketFare($sid)
    {
        $info['table']  = APPLICATIONS_TBL.' AS AT LEFT JOIN ' . TICKETS_TBL . ' AS TT ON (AT.uid=TT.uid)'; 
        $info['debug']  = false;
        $info['fields'] = array('SUM(TT.ticket_fare) AS total_ticket_fare');
        $info['where']  = 'AT.application_status = ' . q('Accepted') . '  ORDER BY AT.country';

        $result = select($info);
        
        return $result[0]->total_ticket_fare;
    }
    
    function saveGuardianDetails($uid)
    {
        $doc_id                   = saveGuardianDocs();
        $data                     = getUserDataSet(GUARDIAN_TBL);
        $data['uid']              = $uid;
        $data['guardian_doc_id']  = $doc_id;
        
        $info['table']  = GUARDIAN_TBL;
        $info['debug']  = false;
        $info['data']   = $data;
        
        if (isRecordExistsByUID($uid, GUARDIAN_TBL))
        {
            $info['where']   = 'uid = ' . $uid;
            update($info);
        }    
        else
        {
            insert($info);
        }
        
    }
    
    function saveGuardianDocs()
    {
        if($_FILES['guardian_income_tax']['size'] > 0)
        {
            $_FILES['document'] = $_FILES['guardian_income_tax'];
            
            $thisDoc = new DocumentEntity();
            $doc_id  = $thisDoc->addDocument();
            
            return $doc_id;
        }
        
        return;
    }
    
    function isRecordExistsByUID($uid, $table_name)
    {
        $info['table']  = $table_name;
        $info['debug']  = false;
        $info['where']  = 'uid = ' . $uid;
        
        $result = select($info);
        
        if ( $result )
        {
            return true;
        }
        
        return false;
    }
    
    function saveApplicationDetails($uid)
    {
        
        $data                       = getUserDataSet(APPLICATIONS_TBL);
        $data['uid']                = $uid;
        $data['sid']                = 1;     
        $data['acceptance_doc_id']  = saveAttachment($_FILES['acceptance_letter']);
        $data['scholarship_doc_id'] = saveAttachment($_FILES['scholarship_letter']);
        $data['enroll_doc_id']      = saveAttachment($_FILES['enroll_certification']);
        $data['i20_doc_id ']        = saveAttachment($_FILES['i20']);
        $data['create_date']        = date('Y-m-d');
        $data['application_status'] = getUserField('submitted') ? 'Pending': 'Not Submitted';
        $data['submit_date']        = getUserField('submitted') ? date('Y-m-d'): null;
        
        
        $info['table']  = APPLICATIONS_TBL;
        $info['debug']  = false;
        $info['data']   = $data;
        
        if ( isRecordExistsByUID($uid, APPLICATIONS_TBL) )
        {
            $info['where'] = 'uid = ' . $uid . ' AND sid = ' . $data['sid']; 
            
            update($info);
        } 
        else
        {
            insert($info);
        }
    }
    
    function saveAcademicQualificationsDetails($uid)
    {
        $info['table']  = ACADEMIC_QUALIFICATIONS_TBL;
        $info['debug']  = false;
        
        foreach( $_REQUEST as $key => $value)
	{
            if( preg_match('/degree_(\d+)/', $key, $matches))
            {
                $id      = $matches[1];
                $aq_id   = $_REQUEST['aqid_' . $id];

                $data['uid']             = $uid;
                $data['degree']          = $_REQUEST['degree_' . $id];
                $data['attachmentname']  = $_REQUEST['attachmentname_' . $id];
                $data['degree']          = $_REQUEST['degree_' . $id];
                $data['doc_id']          = saveAttachment($_FILES['academicfiles_'.$id]);
                
                $info['data']  = $data;
                if($aq_id)
                {
                    $info['where']  = "id = " . $aq_id;
                    update($info);
                }
                else 
                {
                    insert($info);
                }
                
            }
            
            //dumpVar($data);
            
        }
    }
    
    function saveTicketFareDetails($uid)
    {
        $data                    = getUserDataSet(TICKETS_TBL);
        $data['uid']             = $uid;
        $data['ticket_doc_id']  = saveAttachment($_FILES['ticket_doc']);
        $data['create_date']     = date('Y-m-d');
        
        $info['table']  = TICKETS_TBL;
        $info['debug']  = false;
        $info['data']   = $data;
        
        if ( isRecordExistsByUID($uid, TICKETS_TBL) )
        {
            $info['where'] = 'uid = ' . $uid; 
            
            update($info);
        } 
        else
        {
            insert($info);
        }
        
    }


    function saveAttachment($file)
    {
        if($file['size'] > 0)
        {
            $_FILES['document'] = $file;
            
            $thisDoc = new DocumentEntity();
            $doc_id  = $thisDoc->addDocument();
            
            return $doc_id;
        }
        
        return;
    }
?>