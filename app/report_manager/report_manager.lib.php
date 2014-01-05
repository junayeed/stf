<?php
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
                             'G'=>'Alternate', 'H'=>'Page', 'I'=>'Qty', 'J'=>'Space Allocated', 'K'=>'Total', 'L'=>'Status');
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
            $filename  = 'excel_inventory_report.xlsx';
        }
        else if ($data['cmd'] == 'pdf')
        {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "PDF");
            $filename  = 'pdf_inventory_report.pdf';
        }

        //Define current and number format. currency format, &euro; with < 0 being in red color
        $currencyFormat = '#,#0.#0;[Red]-#,#0.#0';

        // number format, with thousands seperator and two decimal points.
        $numberFormat = '#,#0.##0;[Red]-#,#0.##0';

        // writer will create the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();

        // rename the sheet
        $objSheet->setTitle('Order Details');
        
        $objSheet->getColumnDimension('A')->setWidth('8');
        $objSheet->getColumnDimension('B')->setWidth('20');
        $objSheet->getColumnDimension('C')->setWidth('45');
        $objSheet->getColumnDimension('D')->setWidth('12');
        $objSheet->getColumnDimension('E')->setWidth('12');
        $objSheet->getColumnDimension('F')->setWidth('12');
        $objSheet->getColumnDimension('G')->setWidth('10');
        $objSheet->getColumnDimension('H')->setWidth('6');
        $objSheet->getColumnDimension('I')->setWidth('6');
        $objSheet->getColumnDimension('J')->setWidth('15');
        $objSheet->getColumnDimension('K')->setWidth('15');
        $objSheet->getColumnDimension('L')->setWidth('8');
        
        $row = 1;
        
        // print Report Hearder and Sub Headers --  START
        $objSheet->mergeCells('A'.$row.':L'.$row);
        $objSheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(14);
        $objSheet->getCell('A'.$row)->setValue('Inventory Items Summary');
        $row++;
        $objSheet->mergeCells('A'.$row.':L'.$row);
        $objSheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row.':L'.$row)->getFont()->setBold(true)->setSize(14);
        $objSheet->getCell('A'.$row)->setValue('Discover Magazine Ltd.');
        
        $row++;
        if ( $data['magazine'] == '') $magazine = 'All Magazine';
        else if ($data['magazine'] == 0) $magazine = 'No Magazine';
        else $magazine = $data['order_list'][0]->magazine_abvr;
        
        //$start_month = $data['start_month'] != '' ? $data['start_month'] : '-';
        //$end_month   = $data['end_month']   != '' ? $data['end_month']   : '-';
        
        $objSheet->mergeCells('A'.$row.':L'.$row);
        $objSheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
        $objSheet->getCell('A'.$row)->setValue('Magazine: ' . $magazine);
        $row++;
        
        if ($data['status'])
        {
            $objSheet->mergeCells('A'.$row.':L'.$row);
            $objSheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
            $objSheet->getCell('A'.$row)->setValue('Status: ' . $data['status']);
            $row++;
        }
        if ($data['issue_month'])
        {
            $objSheet->mergeCells('A'.$row.':L'.$row);
            $objSheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
            $objSheet->getCell('A'.$row)->setValue('Issue Month: ' . $data['issue_month']);
            $row++;
        }
        if ($data['start_month'])
        {
            $objSheet->mergeCells('A'.$row.':L'.$row);
            $objSheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
            $objSheet->getCell('A'.$row)->setValue('Start Month: ' . $data['start_month']);
            $row++;
        }
        if ($data['end_month'])
        {
            $objSheet->mergeCells('A'.$row.':L'.$row);
            $objSheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
            $objSheet->getCell('A'.$row)->setValue('End Month: ' . $data['end_month']);
            $row++;
        }
        // print Report Hearder and Sub Headers --  END
        
        $row+=2;
        // print the headers -- START
        $objSheet->getStyle('A'.$row.':L'.$row)->getFont()->setBold(true)->setSize(10);
        $objSheet->getStyle('A'.$row.':L'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
        
        foreach($headerArray as $key => $value)
        {
            $objSheet->getCell($key.$row)->setValue($value);
        }
        // print the headers -- END
        
        $row++;
        // print the data
        foreach($data['order_list'] as $oKey => $oValue)    
        {
            $objSheet->getStyle('A'.$row.':L'.$row)->getFont()->setSize(10);
            $objSheet->getCell('A'.$row)->setValue($oValue->id);
            $objSheet->getCell('B'.$row)->setValue($oValue->company_name);
            $objSheet->getCell('C'.$row)->setValue($oValue->description);
            $objSheet->getCell('D'.$row)->setValue($oValue->magazine_abvr);
            $objSheet->getCell('E'.$row)->setValue($oValue->start_month);
            $objSheet->getCell('F'.$row)->setValue($oValue->end_month);
            $objSheet->getCell('G'.$row)->setValue($oValue->alternative);
            $objSheet->getCell('H'.$row)->setValue($oValue->page);
            $objSheet->getCell('I'.$row)->setValue($oValue->qty);
            $objSheet->getCell('J'.$row)->setValue($oValue->qty_per_unit);
            $objSheet->getCell('K'.$row)->setValue($oValue->total);
            $objSheet->getCell('L'.$row)->setValue($oValue->status);
            $objSheet->getStyle('A'.$row.':L'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            $row++;
        }
        
        $objPHPExcel->getActiveSheet()->getStyle('K7:K'.$row)->getNumberFormat()->setFormatCode($currencyFormat);
        $objPHPExcel->getActiveSheet()->getStyle('J7:J'.$row)->getNumberFormat()->setFormatCode($numberFormat);
        
        // print the grand total
        $objSheet->mergeCells('A'.$row.':I'.$row);
        $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(10);
        $objSheet->getCell('A'.$row)->setValue('Grand Total'); 
        $objSheet->getStyle('J'.$row)->getFont()->setBold(true)->setSize(10);
        $objSheet->getCell('J'.$row)->setValue('=SUM(J5:J'.($row-1).')' ); 
        $objSheet->getStyle('K'.$row)->getFont()->setBold(true)->setSize(10);
        $objSheet->getCell('K'.$row)->setValue('=SUM(K5:K'.($row-1).')' ); 
        $objSheet->getStyle('A'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
        $objSheet->getStyle('A'.$row.':L'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
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