<?php

namespace App\Http\Controllers\financial;

use Controller, Auth, Storage, Carbon, PDF, Image;
use Illuminate\Http\Request;
use App\Models\{Payroll,Company_information};

class PreviewEmployeesController extends Controller
{
    private $DataPdf, $CompanyInformation, $border = 0;

    public function PreviewPayroll(Request $Request)
    {
        $this->DataPdf = Payroll::find($Request->PayrollID);
        $this->CompanyInformation = Company_information::where('type','pub')->first();

        if ($this->CheckPermission('ManagementFinancialEmployees') == true) {
            $this->InitinalHeaderFooter();
            $this->InitinalPDF();
            $this->GeneralInfo();
            $this->Signature();
            $this->EndPDF();

        }
        else {
            return view('errors/AccessDenied');
        }
    }
    ///////////////////////////////////////  All Functions For Preview Payroll /////////////////////////////////////////

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
            $img->save('uploads/Temp/payroll-'. $this->DataPdf->id . '.jpg');
            $pdf->Image(public_path('uploads/Temp/payroll-'. $this->DataPdf->id . '.jpg'), 0, 0, $this->DataPdf->pdf_template->width, $this->DataPdf->pdf_template->height, '', '', '', false, 300, '', false, false, 0);

            // restore auto-page-break status
            $pdf->SetAutoPageBreak(TRUE, $bMargin);
            // set the starting point for the page content
            $pdf->setPageMark();

            // set font
            PDF::SetFont($this->DataPdf->pdf_template->font_type->name, '', $this->DataPdf->pdf_template->font_size);

            $logo = '<img src="' . $this->CompanyInformation->logo . '" width="130">';
            PDF::writeHTMLCell(42, '', 140, 17, $logo, $this->border, 0, 0, true, 'C', true);

            $timestamp = '<strong style="font-size: 10px;line-height: 10px; color: #000000;">' . Carbon::now()->parse($this->DataPdf->date)->isoFormat('D/MMMM/YYYY'). '</strong>';
            PDF::writeHTMLCell(30, 2, 5, 10, $timestamp, $this->border, 0, 0, true, 'J', true);

            $company = '
            <style>
                hr {
                    display: block;
                    margin-top: 0.5em;
                    margin-bottom: 0.5em;
                    margin-left: 100px;
                    margin-right: 100px;
                    border-style: inset;
                    border-width: 1px;
                    color:gray;
                    background-color:gray;
                }
            </style><br><hr>
            <table><tbody><tr><td>
                <strong style="font-size: 10px;">'.$this->CompanyInformation->address .' </strong><br>
                <strong style="font-size: 10px;">'.' Tel:  '. $this->CompanyInformation->phone .' – Mob:  '.$this->CompanyInformation->mobile.' </strong>
                <strong style="font-size: 10px;">- Website: </strong>
                <strong style="color: blue; font-size: 10px;"><i>'. $this->CompanyInformation->website .'</i></strong>
                <strong style="font-size: 10px;">- E-mail: </strong>
                <strong style="color: blue; font-size: 10px;"><i>'. $this->CompanyInformation->email .'</i></strong>
            </td></tr></tbody></table>';
            PDF::writeHTMLCell(190, '', 10, 55, $company, $this->border, 0, 0, true, 'L', true);
        });

        PDF::setFooterCallback(function($pdf)
        {
            $pdf->SetY(-25);
            $footer = '
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
                    text-align:center;
                    border:1px solid #C0C0C0;
                    color:#ffffff;
                    padding:5px;
                    background-color:#edaa04;
                    font-size:16px;
                }
                </style>
                <table class="demo"><thead><tr>
                    <th colspan="2"><strong>TOTAL:  '.' '.$this->DataPdf->employee->currencie->symbol .' '.$this->DataPdf->total.'</strong></th>
                </tr></thead><tbody></tbody></table>';
            PDF::writeHTMLCell(75, 2, 120, '', $footer, 1, 0, 0, true, 'L', true);
        });
	}

	public function InitinalPDF()
    {
        // set document information
        PDF::SetCreator('Frontier ERP System.');
        PDF::SetAuthor('Frontier IBS Inc.');
        PDF::SetTitle($this->DataPdf->employee->first_name."'s Payroll");
        PDF::SetSubject($this->CompanyInformation->name);
        // set default monospaced font
        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(0);
        PDF::SetFooterMargin(0);

        // set auto page breaks
        PDF::SetAutoPageBreak(TRUE, 30);
        //PDF::SetAutoPageBreak(FALSE, 0);

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
        $employee = '
            <style>
                table tr {
                  border: 1px solid black;
                }
                .test{
                    text-align:left;
                }
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
                    font-size:18px
                }
                .demo td {
                    border:1px solid #C0C0C0;
                    text-align:center;
                    padding:5px;
                    justify-content: center;
                    font-size:12px;
                }
            </style>
            <table class="demo">
                <thead>
                    <tr>
                        <th><strong>Employee Name: '.' '.$this->DataPdf->employee->first_name.' '.$this->DataPdf->employee->middle_name.' '.$this->DataPdf->employee->last_name.'</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Department:  '.$this->DataPdf->employee->position->department->name.' '.'<br>Branch:  '.$this->DataPdf->employee->position->department->branch->name.'</strong></td>
                    </tr>
                </tbody>
            </table><br><br><br>';
        PDF::writeHTMLCell(120, '', 10, 80, $employee, $this->border,1, 0, true, 'L', true);

        $general_info = '
            <style>
                table tr {
                  border: 1px solid black;
                }
                .test{
                    text-align:left;
                }
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
                    text-align:center;
                    border:1px solid #C0C0C0;
                    color:#ffffff;
                    padding:5px;
                    background-color:#db5d20;
                    font-size:14px
                }
                .demo td {
                    border:1px solid #C0C0C0;
                    text-align:center;
                    padding:5px;
                    justify-content: center;
                    font-size:12px;
                }
            </style>
            <table class="demo">
                <thead>
                    <tr>
                        <th colspan="2"><strong>Date</strong></th>
                        <th colspan="2"><strong>Basic Salary</strong></th>
                        <th colspan="5"><strong>Notes</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2"><strong> '. Carbon::parse($this->DataPdf->date)->isoFormat('Do MMMM YYYY').'</strong></td>
                        <td colspan="2"><strong> '.$this->DataPdf->employee->currencie->symbol .' '. $this->DataPdf->salary->basic .'</strong></td>
                        <td colspan="5">'. $this->DataPdf->notes .'</td>
                    </tr>
                </tbody>
            </table><br><br><br>';
        PDF::writeHTMLCell(190, '', 10, '', $general_info, $this->border,1, 0, true, 'C', true);

        $details = '
            <style>
                table tr {
                  border: 1px solid black;
                }
                .test{
                    text-align:left;
                }
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
                    text-align:center;
                    border:1px solid #C0C0C0;
                    color:#ffffff;
                    padding:5px;
                    background-color:#3f9c55;
                    font-size:14px
                }
                .demo td {
                    border:1px solid #C0C0C0;
                    text-align:center;
                    padding:5px;
                    justify-content: center;
                    font-size:12px;
                }
            </style>
            <table class="demo">
                <thead>
                    <tr>
                        <th colspan="2"><strong>Description</strong></th>
                        <th colspan="2"><strong>Date</strong></th>
                        <th colspan="2"><strong>Amount</strong></th>
                        <th colspan="5"><strong>Notes/Reason</strong></th>
                    </tr>
                </thead>
                <tbody>';
                    foreach ($this->DataPdf->increments as $key => $increment) {
                        $details .= '
                        <tr>
                            <td colspan="2"><strong> Increment </strong></td>
                            <td colspan="2"><strong> '. Carbon::parse($increment->date)->isoFormat('Do MMMM YYYY').'</strong></td>
                            <td colspan="2"><strong> '.$this->DataPdf->employee->currencie->symbol .' '. $increment->amount .'</strong></td>
                            <td colspan="5"><strong> '. $increment->notes .'</strong></td>
                        </tr>
                        ';
                    }

                    foreach ($this->DataPdf->deductions as $key => $deduction) {
                        $details .= '
                        <tr>
                            <td colspan="2"><strong> Deduction </strong></td>
                            <td colspan="2"><strong> '. Carbon::parse($deduction->date)->isoFormat('Do MMMM YYYY').'</strong></td>
                            <td colspan="2"><strong> '.$this->DataPdf->employee->currencie->symbol .' '. $deduction->amount .'</strong></td>
                            <td colspan="5"><strong> '. $deduction->notes .'</strong></td>
                        </tr>
                        ';
                    }

                    foreach ($this->DataPdf->loans_payrolls as $key => $loans) {
                        $details .= '
                        <tr>
                            <td colspan="2"><strong> Loan </strong></td>
                            <td colspan="2"><strong> '. Carbon::parse($loans->loan->date)->isoFormat('Do MMMM YYYY').'</strong></td>
                            <td colspan="2"><strong> '.$this->DataPdf->employee->currencie->symbol .' '. $loans->amount .'</strong></td>
                            <td colspan="5"><strong> '. $loans->loan->notes .'</strong></td>
                        </tr>
                        ';
                    }

        $details .= '</tbody></table><br>';
        PDF::writeHTMLCell(190, '', 10, '', $details, $this->border,1, 0, true, 'C', true);

    }

    public function Signature()
    {
        if(!empty($this->DataPdf->employee_issued->sign)){
            $accounting_signature = Image::make(Storage::get($this->DataPdf->employee_issued->sign));
            $accounting_signature->save('uploads/Temp/accounting-signature-'. $this->DataPdf->id . '.png');
        }

        if(!empty($this->DataPdf->signature)){
            $employee_signature = Image::make(Storage::get($this->DataPdf->signature));
            $employee_signature->save('uploads/Temp/employee-signature-'. $this->DataPdf->id . '.png');
        }

        $Signature_Payroll = '
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
                text-align:center;
                border:1px solid #C0C0C0;
                color:#ffffff;
                padding:5px;
                background-color:#2f83c3;
                font-size:14px;
            }
            .demo td {
                border:1px solid #C0C0C0;
                text-align:center;
                padding:5px;
                justify-content: center;
                font-size:11px;
            }
            </style>
            <table class="demo">
                <thead>
                    <tr>
                        <th colspan="2"><strong>Accounting Employee</strong></th>
                        <th colspan="2"><strong>'.config('app.name').' Employee</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align:left">Employee Name:</td>
                        <td style="text-align:left">'.$this->DataPdf->employee_issued->first_name.'  '.$this->DataPdf->employee_issued->middle_name.' '.$this->DataPdf->employee_issued->last_name.'</td>
                        <td style="text-align:left">Employee Name:</td>
                        <td style="text-align:left">'.$this->DataPdf->employee->first_name.' '.$this->DataPdf->employee->middle_name.' '.$this->DataPdf->employee->last_name.'</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center">Signature:</td>
                        <td colspan="2" style="text-align:center">Signature:</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center; background-color:#fefefe;">';
                        if(!empty($this->DataPdf->employee_issued->sign))
                        {
                            $Signature_Payroll .= '<img src="' . public_path('uploads/Temp/accounting-signature-'. $this->DataPdf->id . '.png') . '" width="200" height="85">';
                        }
                        $Signature_Payroll .= '
                        </td>

                        <td colspan="2" style="text-align:center; background-color:#fefefe;">';
                        if(!empty($this->DataPdf->signature))
                        {
                            $Signature_Payroll .= '<img src="' . public_path('uploads/Temp/employee-signature-'. $this->DataPdf->id . '.png') . '">';
                        }
                        $Signature_Payroll .= '
                        </td>
                    </tr>
                </tbody>
            </table>';
        PDF::writeHTMLCell(190, '', 10, 210, $Signature_Payroll, $this->border,0, 0, true, 'C', true);
	}

	public function EndPDF()
    {
		// reset pointer to the last page
        PDF::lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        PDF::Output('Payroll-' . $this->DataPdf->id . '.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+
	}
}
