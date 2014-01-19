<?php
    function getMagazineList()
    {
        $info['table']  = MAGAZINES_TBL;
        $info['debug']  = true;
        $info['fields'] = array('id', 'magazine_abvr');
        
        $result = select($info);
        
        if( !empty($result) )
        {
            foreach($result as $key => $value)
            {
                $retData[$value->id] = $value->magazine_abvr;
            }
        }
        
        return $retData;
    }
?>