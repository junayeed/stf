<?php
    function getAcademicQualificationList($uid)
    {
        $info['table']  = ACADEMIC_QUALIFICATIONS_TBL;
        $info['debug']  = true;
        $info['where']  = 'uid = ' . $uid;
        
        $result = select($info);
        
        foreach($result as $key => $value)
        {
            if ( $value->doc_id_c > 0) $result[$key]->file_c = getFileLocation($value->doc_id_c, $uid);
            if ( $value->doc_id_t > 0) $result[$key]->file_t = getFileLocation($value->doc_id_t, $uid);
            
            //dumpVar($value->doc_id_c);
        }
        return $result;
    }
?>