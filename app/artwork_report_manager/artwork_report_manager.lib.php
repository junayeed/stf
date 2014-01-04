<?php
    /*function isOrderShowable($order, $issue_month)
    {
        if( ($issue_month >= $order->start_month && $issue_month <= $order->end_month) )
        {
            //echo_br("Start Month = " . $order->start_month . "End Month = " . $order->end_month . "  Issue Month = " . $issue_month . ' ORDER ID= ' . $order->id);
            return true;
        }
        
        return false;
    }*/
    
    function convertNumber2Month($monthNumber)
    {
        if ($monthNumber == 'Ongoing')
        {
            return $monthNumber;
        }
        
        $monthArray = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
        $month      = substr($monthNumber, 2, 2);
        $year       = substr($monthNumber, 0, 2);
        
        if ($month && $year)
        {
            return $monthArray[$month] . ' ' . $year;
        }
        
        return '';
    }
    
    function convertMonth2Number($monthStr)
    {
        if ($monthStr == 'Ongoing')
        {
            return $monthStr;
        }
        
        $monthArray         = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
        list($month, $year) = explode(" ", $monthStr);
        $key                = array_search($month, $monthArray);
        
        return $year.$key;
    }
    
    function MakeExcelorPDF($data)
    {
        $headerArray = array('A'=>'Order #', 'B'=>'Customer Name', 'C'=>'Description', 'D'=>'Magazine', 'E'=>'Start Month', 'F'=>'End Month', 
                             'G'=>'Size', 'H'=>'Shape', 'I'=>'Position', 'J'=>'Status', 'K'=> 'Instruction');
        // create new PHPExcel object
        $objPHPExcel = new PHPExcel;

        // set default font
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');

        // set default font size
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
        
        // set the page orientation
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // create the writer
        if ($data['cmd'] == 'excel')
        {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
            $filename  = 'excel_artwork_inventory_report.xlsx';
        }
        else if ($data['cmd'] == 'pdf')
        {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "PDF");
            $filename  = 'pdf_artwork_inventory_report.pdf';
        }

        //Define current and number format. currency format, &euro; with < 0 being in red color
        $currencyFormat = '£ #,#0.#0;[Red]-#,#0.#0';

        // number format, with thousands seperator and two decimal points.
        $numberFormat = '#,#0.##0;[Red]-#,#0.##0';

        // writer will create the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();

        // rename the sheet
        $objSheet->setTitle('Artwork Details');
        
        $objSheet->getColumnDimension('A')->setWidth('8');
        $objSheet->getColumnDimension('B')->setWidth('20');
        $objSheet->getColumnDimension('C')->setWidth('45');
        $objSheet->getColumnDimension('D')->setWidth('12');
        $objSheet->getColumnDimension('E')->setWidth('12');
        $objSheet->getColumnDimension('F')->setWidth('12');
        $objSheet->getColumnDimension('G')->setWidth('8');
        $objSheet->getColumnDimension('H')->setWidth('8');
        $objSheet->getColumnDimension('I')->setWidth('15');
        $objSheet->getColumnDimension('J')->setWidth('15');
        $objSheet->getColumnDimension('K')->setWidth('40');
        
        $row = 1;
        
        // print Report Hearder and Sub Headers --  START
        $objSheet->mergeCells('A'.$row.':K'.$row);
        $objSheet->getStyle('A'.$row.':K'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(14);
        $objSheet->getCell('A'.$row)->setValue('Inventory Items Summary');
        $row++;
        $objSheet->mergeCells('A'.$row.':K'.$row);
        $objSheet->getStyle('A'.$row.':K'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row.':K'.$row)->getFont()->setBold(true)->setSize(14);
        $objSheet->getCell('A'.$row)->setValue('Discover Magazine Ltd.');
        
        $row++;
        if ( $data['magazine'] == '') $magazine = 'All Magazine';
        else if ($data['magazine'] == 0) $magazine = 'No Magazine';
        else $magazine = $data['order_list'][0]->magazine_abvr;
        
        //$start_month = $data['start_month'] != '' ? $data['start_month'] : '-';
        //$end_month   = $data['end_month']   != '' ? $data['end_month']   : '-';
        
        $objSheet->mergeCells('A'.$row.':K'.$row);
        $objSheet->getStyle('A'.$row.':K'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
        $objSheet->getCell('A'.$row)->setValue('Magazine: ' . $magazine);
        $row++;
        
        if ($data['status'])
        {
            $objSheet->mergeCells('A'.$row.':K'.$row);
            $objSheet->getStyle('A'.$row.':K'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
            $objSheet->getCell('A'.$row)->setValue('Status: ' . $data['status']);
            $row++;
        }
        if ($data['issue_month'])
        {
            $objSheet->mergeCells('A'.$row.':K'.$row);
            $objSheet->getStyle('A'.$row.':K'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
            $objSheet->getCell('A'.$row)->setValue('Issue Month: ' . $data['issue_month']);
            $row++;
        }
        if ($data['start_month'])
        {
            $objSheet->mergeCells('A'.$row.':K'.$row);
            $objSheet->getStyle('A'.$row.':K'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
            $objSheet->getCell('A'.$row)->setValue('Start Month: ' . $data['start_month']);
            $row++;
        }
        if ($data['end_month'])
        {
            $objSheet->mergeCells('A'.$row.':J'.$row);
            $objSheet->getStyle('A'.$row.':J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
            $objSheet->getCell('A'.$row)->setValue('End Month: ' . $data['end_month']);
            $row++;
        }
        // print Report Hearder and Sub Headers --  END
        
        $row+=2;
        // print the headers -- START
        $objSheet->getStyle('A'.$row.':K'.$row)->getFont()->setBold(true)->setSize(10);
        $objSheet->getStyle('A'.$row.':K'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
        
        foreach($headerArray as $key => $value)
        {
            $objSheet->getCell($key.$row)->setValue($value);
        }
        // print the headers -- END
        
        $row++;
        // print the data
        foreach($data['order_list'] as $oKey => $oValue)    
        {
            $objSheet->getStyle('A'.$row.':K'.$row)->getFont()->setSize(10);
            $objSheet->getCell('A'.$row)->setValue($oValue->id);
            $objSheet->getCell('B'.$row)->setValue($oValue->company_name);
            $objSheet->getCell('C'.$row)->setValue($oValue->description);
            $objSheet->getCell('D'.$row)->setValue($oValue->magazine_abvr);
            $objSheet->getCell('E'.$row)->setValue($oValue->start_month);
            $objSheet->getCell('F'.$row)->setValue($oValue->end_month);
            $objSheet->getCell('G'.$row)->setValue($oValue->qty_per_unit);
            $objSheet->getCell('H'.$row)->setValue($oValue->shape);
            $objSheet->getCell('I'.$row)->setValue($oValue->position);
            $objSheet->getCell('J'.$row)->setValue($oValue->status);
            $objSheet->getCell('K'.$row)->setValue($oValue->instruction);
            $objSheet->getStyle('A'.$row.':J'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$row)->getAlignment()->setWrapText(true);
            $objSheet->getStyle('A'.$row.':K'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            $row++;
        }
        
        
        // write the file
//        header("Pragma: public");
//        header("Expires: 0");
//        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//        header("Content-Type: application/force-download");
//        header("Content-Type: application/octet-stream");
//        header("Content-Type: application/download");
//        header("Content-Disposition: attachment;filename=sheet1111.xls");
//        header("Content-Transfer-Encoding: binary ");

//        header('Content-Type: application/pdf');
//        header('Content-Disposition: attachment;filename="sheet1111.pdf"');
//        header('Cache-Control: max-age=0');
        header('Content-Disposition: attachment;filename="' . $filename. '"');
        header('Content-Type: application/pdf');
        header('Content-Type: text/plain; charset=utf-8');
        $objWriter->save($_SERVER['DOCUMENT_ROOT'].'/files/'.$filename);
        header ('Location: /files/'.$filename);
    }
?>