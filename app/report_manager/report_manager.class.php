<?php

/**
 * File: report_manager.class.php
 *
 */

/**
 * The reportManagerApp application class
 */

class reportManagerApp extends DefaultApplication
{
    function run()
    {
        $cmd = getUserField('cmd');

        switch ($cmd)
        {
            case 'list'         : $screen = $this->showList();           break;
            case 'pdf'          : $screen = $this->showEditor();         break;
            case 'excel'        : $screen = $this->showEditor();         break;
            default             : $screen = $this->showEditor($msg);     break;
        }

        // Set the current navigation item
        $this->setNavigation('user');

        if ($cmd == 'list' || $cmd == 'pdf')
        {
            echo $screen;
        }
        else
        {
            echo $this->displayScreen($screen);
        }

        return true;
    }

    /**
    * Shows User Editor
    * @param message
    * @return user editor template
    */
    function showEditor($msg = '')
    {
        $start_month             = getUserField('start_month');
        $end_month               = getUserField('end_month');
        $issue_month             = getUserField('issue_month');
        $data['magazine']        = getUserField('magazine');
        $data['status']          = getUserField('status');
        $data['customer_name']   = getUserField('customer_name');
        $data['product_id']      = getUserField('product_id');
        $data['magazine_list']   = getMagazineList();
        $data['cmd']             = getUserField('cmd');

        $startMonthClaus   = ' AND 1';
        $endMonthClause    = ' AND 1';
        $statusClause      = ' AND 1';
        $magazineClause    = ' AND 1';
        $customerClause    = ' AND 1';
        $productClause     = ' AND 1';

        if ( $data['customer_name']) $customerClause = " AND CT.company_name  LIKE '%" . $data['customer_name'] . "%'";
        if ( $start_month ) $startMonthClaus = ' AND CAST(start_month AS UNSIGNED) = '. $start_month;
        if ( $end_month && $end_month != 'Ongoing' ) $endMonthClause = ' AND CAST(end_month AS UNSIGNED) = ' . $end_month . ' AND end_month != ' . q('Ongoing');
        if ( $end_month && $end_month == 'Ongoing' ) $endMonthClause = ' AND end_month = ' . q('Ongoing');
        if ( $data['status'] ) $statusClause = ' AND ODT.status = ' . q($data['status']);
        if ( $data['product_id'] ) $productClause = ' AND PT.product_code = ' . q($data['product_id']);
        
        if ($data['magazine'])
        {    
            if ($data['magazine'] == 'All')
            {
                $magazineClause .= ' AND ODT.magazine_code != 0';
            }
            else if ($data['magazine'] == 'No')
            {
                $magazineClause .= ' AND ODT.magazine_code = 0';
            }
            else
            {
                $magazineClause .= ' AND ODT.magazine_code = ' . $data['magazine'];
            }
        }
        
        $info['table']   = ORDER_DETAILS_TBL . ' AS ODT LEFT JOIN ' . PRODUCT_TBL . ' AS PT ON (ODT.product_id=PT.id) LEFT JOIN ' . MAGAZINES_TBL . ' AS MT ON (ODT.magazine_code=MT.id) LEFT JOIN ' . CUSTOMERS_TBL . ' AS CT ON (ODT.customer_id = CT.id)';
        $info['debug']   = false;
        $info['where']   = '1' . $startMonthClaus . $endMonthClause . $statusClause . $magazineClause . $customerClause . 
                                 $productClause . ' ORDER BY ODT.id DESC';
        $info['fields']  = array('ODT.id', 'PT.product_code', 'PT.description', 'PT.product_status', 'MT.magazine_abvr', 'ODT.start_month', 
                                'ODT.end_month', 'ODT.customer_id', 'ODT.alternative', 'ODT.page', 'ODT.qty', 'ODT.unit_price', 
                                'ODT.discount', 'ODT.total', 'ODT.status', 'CT.company_name', 'PT.qty_per_unit');

        $data['orders'] = select($info);
        
        if ($data['orders'])
        {
            foreach($data['orders'] as $key => $value)
            {
                if ( $issue_month && !isOrderShowable($value, $issue_month))
                {
                    continue;
                }
            
                $value->start_month = convertNumber2Month($value->start_month);

                if ( $value->end_month != 'Ongoing')
                {
                    $value->end_month = convertNumber2Month($value->end_month);
                }
                $data['order_list'][] = $value;
            }
        }
        
        $data['start_month'] = $start_month;
        $data['end_month']   = $end_month;
        $data['issue_month'] = $issue_month;
        //dumpVar($data);
        
        //exportToPDF($screen, $subHeader1, $subHeader2, $magazineClause, $oreintation)

        if ($data['cmd'] == 'excel' || $data['cmd'] == 'pdf')
        {    
            //header('Content-Type: text/plain; charset=utf-8');
            //$screen = createPage(PDF_TEMPLATE, $data);
            MakeExcelorPDF($data);
            return;
        }

        return createPage(REPORT_EDITOR_TEMPLATE, $data);
    }
}
?>