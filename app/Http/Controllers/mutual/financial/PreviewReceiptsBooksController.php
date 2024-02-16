<?php

namespace App\Http\Controllers\mutual\financial;

use Controller, Auth, Storage, Carbon, PDF, DNS2D, Image;
use Illuminate\Http\Request;
use App\Models\{Receipts_book,Company_information};

class PreviewReceiptsBooksController extends Controller
{
    private $DataPdf, $CompanyInformation, $border = 0;

    public function PreviewReceiptBook(Request $Request)
    {
        if ($this->CheckPermission('ReceiptsBooksManage') == true)
        {
            $this->DataPdf = Receipts_book::find($Request->ReceiptBookID);
            $this->CompanyInformation = Company_information::where('type','pub')->first();

            $this->InitinalHeaderFooter();
            $this->InitinalPDF();
            $this->GeneralInfo();
            $this->Stamp();
            $this->EndPDF();
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

/////////////////////////////////////  Preview Receipts Books Functions  ///////////////////////////////////////////////////////
    public function InitinalHeaderFooter()
    {
        PDF::setHeaderCallback(function($pdf)
        {
            // get the current page break margin
            $bMargin = $pdf->getBreakMargin();
            // disable auto-page-break
            $pdf->SetAutoPageBreak(false, 0);

            // set bacground image
            $img = Image::make(Storage::get($this->DataPdf->pdf_template->path_template));
            $img->save('uploads/Temp/ReceiptBook'.'-'. $this->DataPdf->id . '.jpg');
            $pdf->Image(public_path('uploads/Temp/ReceiptBook'.'-'. $this->DataPdf->id . '.jpg'), $this->border, 0, $this->DataPdf->pdf_template->width, $this->DataPdf->pdf_template->height, '', '', '', false, 300, '', false, false, 0);

            // restore auto-page-break status
            //$pdf->SetAutoPageBreak(TRUE, $bMargin);

            // set the starting point for the page content
            $pdf->setPageMark();

            // set font
            PDF::SetFont($this->DataPdf->pdf_template->font_type->name, '', $this->DataPdf->pdf_template->font_size);

            $logo = '<img src="' . $this->CompanyInformation->logo . '" width="90">';
            PDF::writeHTMLCell(20, '', 9, 6, $logo, $this->border, 0, 0, true, 'C', true);

            $name = '<strong style="font-size: 16px;">' . $this->CompanyInformation->name . '</strong>';
            PDF::writeHTMLCell(130, '', 40, 3, $name, $this->border, 0, 0, true, 'C', true);

            $second_name = '<span style="font-size: 19px;">' . $this->CompanyInformation->second_name . ' </span>';
            PDF::writeHTMLCell(130, '', 40, 10, $second_name, $this->border, 0, 0, true, 'C', true);

            // set contact
            $contact = '
                <table><tbody><tr><td>
                    <strong style="font-size: 16px;">'.' Tel:  '. $this->CompanyInformation->phone .' – Mob:  '.$this->CompanyInformation->mobile.' <br></strong>
                    <strong style="font-size: 16px;">Website: </strong>
                    <strong style="color: #024690; font-size: 16px;"><i>'. $this->CompanyInformation->website .'</i></strong>
                    <strong style="font-size: 16px;">- E-mail: </strong>
                    <strong style="color: #024690; font-size: 16px;"><i>'. $this->CompanyInformation->email .'</i></strong>
                </td></tr></tbody></table>';
            PDF::writeHTMLCell(149, '', 30, 18, $contact, $this->border, 0, 0, true, 'C', true);

            // set QR
            $QR = Image::make(DNS2D::getBarcodePNG(config('app.VerifyReceipt').$this->DataPdf->date.'/'.$this->DataPdf->serial_number.'/'.$this->DataPdf->employee_id, 'QRCODE'));
            $QR->resize(275, null, function ($constraint) { $constraint->aspectRatio();});
            $QR->save('uploads/Temp/QR_ReceiptBook-'.$this->DataPdf->id.'.png');
            $qr = '<img src="uploads/Temp/QR_ReceiptBook-'.$this->DataPdf->id.'.png" width="75">';
            PDF::writeHTMLCell(20, '', 180, 5,$qr, $this->border, 0, 0, true, 'C', true);
        });

        PDF::setFooterCallback(function($pdf)
        {
            // Position at 15 mm from bottom
            $pdf->SetY(-7);

            $footer = '<strong style="font-size: 14px; color: #ffffff;">'.$this->CompanyInformation->address .' </strong>';
            PDF::writeHTMLCell(195, 2, 7, '', $footer, $this->border, 1, 0, true, 'C', true);
        });

    }

    public function InitinalPDF()
    {
        // set document information
        PDF::SetCreator('Frontier ERP System.');
        PDF::SetAuthor('Frontier IBS Inc.');
        if ($this->DataPdf->type == 1) {
            PDF::SetTitle($this->DataPdf->id ."'s Receipt");
        } else {
            PDF::SetTitle($this->DataPdf->id ."'s Payment Receipt");
        }
        PDF::SetSubject($this->CompanyInformation->name);
        PDF::SetKeywords('Receipts');
        // set default monospaced font
        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(0);
        PDF::SetFooterMargin(0);

        // set auto page breaks
        //PDF::SetAutoPageBreak(TRUE, 30);
        PDF::SetAutoPageBreak(FALSE, 0);

        // set image scale factor
        PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Set Protection File
        PDF::SetProtection(array('modify','copy','assemble','extract','fill-forms','annot-forms'), '', null, 0, null);

        // ---------------------------------------------------------

        // Set Languages
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_language'] = 'ar'; // 'ar' for Arabic
        $lg['w_page'] = 'page';
        PDF::setLanguageArray($lg);

        // set font
        PDF::SetFont($this->DataPdf->pdf_template->font_type->name, '', $this->DataPdf->pdf_template->font_size);

        // add a page
        PDF::AddPage($this->DataPdf->pdf_template->orientation,$this->DataPdf->pdf_template->page_size);

    }

    public function GeneralInfo()
    {
        $NumberAndDate = '<strong style="font-size: 20px;">No.: '.$this->DataPdf->serial_number.'<br>Date: '.Carbon::parse($this->DataPdf->date)->isoFormat('D/MMMM/YYYY').'</strong>';
        PDF::writeHTMLCell(64, '', 12, 45, $NumberAndDate, $this->border, 1, 0, true, 'L', true);

        if ($this->DataPdf->type == 1) {
            $Title = '<strong style="font-size: 30px; color: #b81717;">Receipt Voucher</strong><br><span style="font-size: 30px; color: #b81717;">وصل قبض</span>';

            $Received_Payment = '<strong style="font-size: 17px;">Received From : ....................................................................................................................................... </strong><span style="font-size: 20px;">استلمت من :</span>';
        } else {
            $Title = '<strong style="font-size: 30px; color: #b81717;">Payment Voucher</strong><span style="font-size: 30px; color: #b81717;">وصل دفع</span>';

            $Received_Payment = '<strong style="font-size: 17px;">Payment To: ................................................................................................... </strong><span style="font-size: 20px;">دفعت إلى:</span>';
        }

        PDF::writeHTMLCell(70, '', 76, 35, $Title, $this->border, 1, 0, true, 'C', true);
        PDF::writeHTMLCell(190, '', 12, 68, $Received_Payment, $this->border,1, 0, true, 'C', true);

        PDF::writeHTMLCell(190, '', 12, 80, '<strong style="font-size: 17px;">Amount Of : ......................................................................................................................................... </strong><span style="font-size: 20px;">مبلغاً قدره :</span>', $this->border,1, 0, true, 'C', true);

        PDF::writeHTMLCell(190, '', 12, 90, '<strong style="font-size: 17px;">The Value For : ................................................................................................................................... </strong><span style="font-size: 20px;">وذلك عن قيمة :</span>', $this->border,1, 0, true, 'C', true);

        PDF::writeHTMLCell(125, '', 48, 67, '<span style="font-size: 17px;">'.$this->DataPdf->customer.'</span>', $this->border,1, 0, true, 'C', true);
        PDF::writeHTMLCell(130, '', 40, 79, '<span style="font-size: 15px;">'.$this->DataPdf->amount_write.'</span>', $this->border,1, 0, true, 'C', true);
        PDF::writeHTMLCell(123, '', 47, 89, '<span style="font-size: 15px;">'.$this->DataPdf->notes.'</span>', $this->border,1, 0, true, 'C', true);

        PDF::writeHTMLCell(50, '', 15, 105, '<strong style="font-size: 17px;"><br>Receiver Signature</strong><br><span style="font-size: 20px;">توقيع مستلم المبلغ</span>', $this->border,1, 0, true, 'C', true);
        PDF::writeHTMLCell(40, '', 160, 105, '<strong style="font-size: 17px;"><br>Payer Signature</strong><br><span style="font-size: 20px;">توقيع مسلّم المبلغ</span>', $this->border,1, 0, true, 'C', true);

        $amount = '
            <style>
            .demo {
                width:100%;
                height:100%;
                border:1px solid #C0C0C0;
                border-collapse:collapse;
                border-spacing:2px;
                padding:5px;
            }
            .demo caption {
                text-align:center;
            }
            .demo th {
                border:1px solid #C0C0C0;
                color:#ffffff;
                padding:5px;
                background-color:#2f83c3;
                font-weight: bold;
                font-size: 25px;
            }
            </style>
            <table class="demo">
                <thead>
                    <tr>
                        <th>'.$this->DataPdf->currencie->symbol.' '.$this->DataPdf->amount.'</th>
                    </tr>
                </thead>
                <tbody></tbody></table>';

        PDF::writeHTMLCell(45, 15, 160, 40, $amount, $this->border,1, 0, true, 'J', true);

        PDF::writeHTMLCell(60, 20, 90, 118, '<strong style="font-size: 14px;">Employee Name:  <br>'.$this->DataPdf->employee->first_name.' '.$this->DataPdf->employee->last_name.'</strong>', $this->border,0, 0, true, 'J', true);

    }

    public function Stamp()
    {
        $stamp = Image::make(Storage::get($this->CompanyInformation->stamp));
        $stamp->save('uploads/Temp/stamp-'.$this->CompanyInformation->id.'.png');
        PDF::Image(public_path('uploads/Temp/stamp-'.$this->CompanyInformation->id.'.png'), 100, 110, 37, 37, 'PNG', '', '', false, 300, '', false, false, 0);
    }

    public function EndPDF()
    {
        // reset pointer to the last page
        PDF::lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        if ($this->DataPdf->type == 1) {
            PDF::Output('Receipt-' . $this->DataPdf->id . '.pdf', 'I');
        } else {
            PDF::Output('Payment-Receipt-' . $this->DataPdf->id . '.pdf', 'I');
        }

        //============================================================+
        // END OF FILE
        //============================================================+

    }

}
