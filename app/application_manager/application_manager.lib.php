<?php
    
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
        $info['debug']  = true;
        $info['where']  = 'uid = ' . $uid;
        
        $result = select($info);
        
        if ( $result )
        {
            return true;
        }
        
        return false;
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