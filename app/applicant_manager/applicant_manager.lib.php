<?php
    function getAcademicQualificationList($uid)
    {
        $info['table']  = ACADEMIC_QUALIFICATIONS_TBL;
        $info['debug']  = false;
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
    
    function MakeExcelorPDF($data)
    {
        $headerArray = array(
                             'A' => '', 
                             'B' => "i) Name of the Applicant\nii) Name & Occupation of the guardian\niii) Address of the Applicant in Bangladesh\niv) Annual income of the guardian",
                             'C' => "i) Did the applicant recieve any grant from the Bangladesh-Sweden Trust Fund earlier? If yes, provide full particulars along with the date and amount\nii)Certificate from the enrolling educational Institution to the effect that no travel expenses has been or would be reimbursed to the applicant.",   
                             'D' => "Educational Qualifications/Division/Grade obtained", 
                             'E' => '', 
                             'F' => '', 
                             'G' => '', 
                             'H' => "Name of the University/Educational Institution abroad where the applicant has been enrolled\nField of Study\nE-mail, Address of caotact person", 
                             'I' => "i) Acceptance letter\nFellowship/Scholarship Award Letter\nEnrolement Certificate\nii) Copy of the duly signed I-20 form if the enrolling education institute is located in USA or \nTOFEL/IELTS/SAT/GRE/GMAT", 
                             'J' => "Cost of one way Ticket (in US$ and BD Taka)\nPhotocopy of air Ticket attached.\n\nFare-\nTax-\nTotal-", 
                             'K' => "Base Fare", 
                             'L' => "75% of one way Air fare",
                             'M' => "Remarks"
                            );
        
        $subHeaderArray = array('A' => '',
                                'B' => '',
                                'C' => '',
                                'D' => 'S.S.C/O Levels/Dakhil',
                                'E' => 'H.S.C/A Levels/Alim/IB',
                                'F' => 'Bachelor/Kamil',
                                'G' => 'Masters/Fazil',
                                'H' => '',
                                'I' => '',
                                'J' => '',
                                'K' => '',
                                'L' => '',
                                'M' => ''
                               );
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
            $filename  = 'bstf_report.xlsx';
        }
        else if ($data['cmd'] == 'pdf')
        {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "PDF");
            $filename  = 'bstf_report.pdf';
        }

        //Define current and number format. currency format, &euro; with < 0 being in red color
        $currencyFormat = '#,#0.#0;[Red]-#,#0.#0';

        // number format, with thousands seperator and two decimal points.
        $numberFormat = '#,#0.##0;[Red]-#,#0.##0';

        // writer will create the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();

        // rename the sheet
        $objSheet->setTitle('BSTF Report');
        
        $objSheet->getColumnDimension('A')->setWidth('8');
        $objSheet->getColumnDimension('B')->setWidth('30');
        $objSheet->getColumnDimension('C')->setWidth('25');
        $objSheet->getColumnDimension('D')->setWidth('11');
        $objSheet->getColumnDimension('E')->setWidth('11');
        $objSheet->getColumnDimension('F')->setWidth('11');
        $objSheet->getColumnDimension('G')->setWidth('11');
        $objSheet->getColumnDimension('H')->setWidth('30');
        $objSheet->getColumnDimension('I')->setWidth('25');
        $objSheet->getColumnDimension('J')->setWidth('20');
        $objSheet->getColumnDimension('K')->setWidth('15');
        $objSheet->getColumnDimension('L')->setWidth('15');
        $objSheet->getColumnDimension('M')->setWidth('15');
        
        $row   = 1; // Set the starting row number 
        $sl_no = 1; // set the applicant serial number
        
        // print Report Hearder and Sub Headers --  START
        $objSheet->mergeCells('A'.$row.':M'.$row);
        $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(14);
        $objSheet->getCell('A'.$row)->setValue('Economic Relations Division');
        $row++;
        
        $objSheet->mergeCells('A'.$row.':M'.$row);
        $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row.':M'.$row)->getFont()->setBold(true)->setSize(14);
        $objSheet->getCell('A'.$row)->setValue('Ministry of Finance');
        $row++;
        
        $objSheet->mergeCells('A'.$row.':M'.$row);
        $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row.':M'.$row)->getFont()->setBold(true)->setSize(14);
        $objSheet->getCell('A'.$row)->setValue('(F & F Section - 03)');
        $row++;
        
        $objSheet->mergeCells('A'.$row.':M'.$row);
        $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row.':M'.$row)->getFont()->setBold(true)->setSize(14);
        $objSheet->getCell('A'.$row)->setValue('Sher-e-Bangla Nagar, Dhaka');
        $row++;
        
        $objSheet->mergeCells('A'.$row.':M'.$row);
        $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row.':M'.$row)->getFont()->setBold(true)->setSize(14);
        $objSheet->getCell('A'.$row)->setValue('Information sheet of the applicants for the travel grant under the Bangladesh-Sweden Trust Fund');
        $row++;
        
        $objSheet->mergeCells('A'.$row.':M'.$row);
        $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getStyle('A'.$row.':M'.$row)->getFont()->setBold(true)->setSize(14);
        $objSheet->getCell('A'.$row)->setValue('for Academic Session 2013');
        $row++;
        // print Report Hearder and Sub Headers --  END
        
        // print the cell headers -- START
        $row+=2;
        $objSheet->getStyle('A'.$row.':M'.$row)->getFont()->setBold(false)->setSize(10);
        $objSheet->getStyle('A'.$row.':M'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setWrapText(true);
        $objSheet->getRowDimension(9)->setRowHeight(130);  // Hardcoded set the row hight of the header row
        $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        
        
        foreach($headerArray as $key => $value)
        {
            $objSheet->getCell($key.$row)->setValue($value);
        }
        
        // MERGE 4 cols of Academic Qualification;
        $objSheet->mergeCells('D'.$row.':G'.$row);

        $row++;
        $objSheet->getStyle('A'.$row.':M'.$row)->getFont()->setBold(false)->setSize(10);
        $objSheet->getStyle('A'.$row.':M'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getRowDimension(10)->setRowHeight(50);   // Hardcoded set the row hight of the sub header row
        $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        
        foreach($subHeaderArray as $key => $value)
        {
            $objSheet->getCell($key.$row)->setValue($value);
            if ( !$value )
            {
                $prev_row = $row - 1;
                $objSheet->mergeCells($key.$prev_row.':'.$key.$row);
                $objSheet->getStyle($key.$row)->getAlignment()->setWrapText(true);
            }
        }
        
        $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setWrapText(true);
        // print the cell headers -- END
        
        $row++;
        // print the data
        foreach($data['list'] as $oKey => $oValue)    
        {
            //echo_br($row); die;
            $objSheet->getCell('B'.$row)->setValue($oKey);
            $objSheet->mergeCells('B'.$row.':M'.$row);
            $objSheet->getStyle('A'.$row.':M'.$row)->getFont()->setBold(true)->setItalic(true)->setSize(14);
            $objSheet->getStyle('A'.$row.':M'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $row++;
            
            foreach($oValue as $key => $value)
            {
                $objSheet->getStyle('A'.$row.':L'.$row)->getFont()->setSize(10);
                $objSheet->getCell('A'.$row)->setValue($sl_no);
                $objSheet->getCell('B'.$row)->setValue("i) " . $value->name . "\nii) S/o. " . $value->guardian_name . ", " . $value->guardian_occupation . "\niii) " . $value->present_address . "\nPh: " . $value->present_phone . "\n" . $value->email . "\niv) ".$value->guardian_income.$value->guardian_doc . "\n");
                $objSheet->getCell('C'.$row)->setValue("i)No"."\nii)" . $value->enroll_doc);
                $objSheet->getCell('D'.$row)->setValue($value->academic_qualification['a']);
                $objSheet->getCell('E'.$row)->setValue($value->academic_qualification['b']);
                $objSheet->getCell('F'.$row)->setValue($value->academic_qualification['c']);
                $objSheet->getCell('G'.$row)->setValue($value->academic_qualification['d']);
                $objSheet->getCell('H'.$row)->setValue($value->university_name . "\n" . $value->subject_desc . "\n" . $value->university_contact);
                $objSheet->getCell('I'.$row)->setValue("i)" . $value->acceptance_doc . "\n" . $value->scholarship_doc . "\nii)".$value->other_degree);
                $objSheet->getCell('J'.$row)->setValue($value->ticket_doc . "\nFare-" . $value->ticket_fare . "\nTax-" . $value->tax . "\nTotal-" . $value->total . "\n" . $value->destination_airport);
//                $objSheet->getCell('K'.$row)->setValue($value->local_fare);  // Comment out by Junayeed on 23/11/2014 upon request on Sobhan, ERD to change the local fare to base fare. 
                $objSheet->getCell('K'.$row)->setValue($value->base_fare);
                $objSheet->getCell('L'.$row)->setValue($value->grant_amount);
                
                if($value->application_status=='Accepted')
                {
                    $objSheet->getCell('M'.$row)->setValue('OK');
                    
                }    
                else
                {
                    $objSheet->getCell('M'.$row)->setValue( $value->remarks);
                }
                    
                $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setWrapText(true);
                $objSheet->getStyle('A'.$row.':M'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objSheet->getStyle('A'.$row.':M'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            
                $row++;
                $sl_no++;
            }
            
        }
        /*
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
        */
        $objWriter->save($_SERVER['DOCUMENT_ROOT'].'/files/'.$filename);
        
        header('Content-Disposition: attachment;filename="' . $filename. '"');
        header('Content-Type: application/pdf');
        header('Content-Type: text/plain; charset=utf-8');
        
        header ('Location: /files/'.$filename);
    }
?>