<?php

namespace App\Http\Controllers\financial;

use Controller, Auth, Storage, Response, File, Carbon;
use Illuminate\Http\Request;
use App\Models\{Employee,Attendance,Deduction,Increment,Loan,Leave,Salarie,Leave_role,Payroll,Loans_payroll,Currencie,Task,Pdf_template};
use App\Models\zkteco\{Personnel_employee,Iclock_transaction};

class ManagementFinancialEmployeesController extends Controller
{
    public function ManagementFinancialEmployees()
    {
        if ($this->CheckPermission('ManagementFinancialEmployees') == true)
        {
            $Employees = Employee::where('id' , '>' , 1)->get();
            $Currencies = Currencie::where('status',1)->get();
            $arr = array('Employees' => $Employees,'Currencies' => $Currencies);
            return view('financial/management_financial_employees/ManagementFinancialEmployees',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    //////////////////////////////////  Attendances ///////////////////////////////
    public function ViewAttendances()
    {
        if ($this->CheckPermission('ManagementFinancialEmployees') == true)
        {
            $Employees = Employee::all();
            $Attendances = Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get();
            $Iclock_transactions = Iclock_transaction::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get();

            if (empty($Attendances->last()->transaction_id)) {

                foreach ($Iclock_transactions as $Iclock_transaction) {
                    if (!empty($Iclock_transaction->emp_code)) {
                        if (!empty($Employees->where('id',$Iclock_transaction->emp_code)->first())) {
                            $newAttendance = new Attendance();
                            $newAttendance -> punch_time = $Iclock_transaction->punch_time;
                            $newAttendance -> punch_state = $Iclock_transaction->punch_state;
                            $newAttendance -> terminal_sn = $Iclock_transaction->terminal_sn;
                            $newAttendance -> terminal_alias = $Iclock_transaction->terminal_alias;
                            $newAttendance -> employee_id = $Iclock_transaction->emp_code;
                            $newAttendance -> transaction_id = $Iclock_transaction->id;
                            $newAttendance -> save();
                        }
                    }
                }

                $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get() , 'Employees' => $Employees);

            }
            elseif ($Attendances->last()->transaction_id <= $Iclock_transactions->last()->id) {
                $count = Iclock_transaction::where('id','>', $Attendances->last()->transaction_id)->count();

                for ($i = 1 ; $i <= $count ; $i++) {
                    if (!empty($Iclock_transactions->find($Attendances->last()->transaction_id + $i)->emp_code)) {
                        if (!empty($Employees->where('id',$Iclock_transactions->find($Attendances->last()->transaction_id + $i)->emp_code)->first())) {
                            $newAttendance = new Attendance();
                            $newAttendance -> punch_time = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->punch_time;
                            $newAttendance -> punch_state = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->punch_state;
                            $newAttendance -> terminal_sn = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->terminal_sn;
                            $newAttendance -> terminal_alias = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->terminal_alias;
                            $newAttendance -> employee_id = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->emp_code;
                            $newAttendance -> transaction_id = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->id;
                            $newAttendance -> save();
                        }
                    }
                }

                $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get() , 'Employees' => $Employees);
            }
            else
            {
                $arr = array('Attendances' => $Attendances , 'Employees' => $Employees);
            }
            return view('financial/management_financial_employees/Attendances',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    //////////////////////////////////  Deductions ///////////////////////////////
    public function Deductions(Request $Request)
    {
        if ($this->CheckPermission('ManagementFinancialEmployees') == true)
        {
            if ($Request->isMethod('get'))
            {
                $Employee = Employee::find($Request->EmployeeID);
                $Deductions = Deduction::where('employee_id',$Request->EmployeeID)->get();
                $arr = array('Deductions' => $Deductions , 'Employee' => $Employee);
                return view('financial/management_financial_employees/Deductions',$arr);
            }
            else
            {
                $newDeduction = new Deduction();
                $newDeduction -> date = $Request->input('date');
                $newDeduction -> amount = $Request->input('amount');
                $newDeduction -> notes = $Request->input('notes');
                $newDeduction -> employee_id = $Request->input('employee_id');
                $newDeduction -> salary_id = $Request->input('salary_id');
                $newDeduction -> save();

                $this->AddToLog('Added New Deduction',$newDeduction->date , $newDeduction->id);

                $Request->session()->put('msgSuccess', 'The Deduction has been added successfully');
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function DeleteDeduction(Request $Request)
    {
        if ($this->CheckPermission('ModifyFinancialEmployees') == true)
        {
            $deleteDeduction = Deduction::find($Request->DeductionID);
            $this->AddToLog('Deleted Deduction',$deleteDeduction->date , $deleteDeduction->id);
            $deleteDeduction -> delete();

            return response()->json('ok');
        }
        else
        {
            return response()->json("Access Denied");
        }

    }

    //////////////////////////////////  Increments ///////////////////////////////
    public function Increments(Request $Request)
    {
        if ($this->CheckPermission('ManagementFinancialEmployees') == true)
        {
            if ($Request->isMethod('get'))
            {
                $Employee = Employee::find($Request->EmployeeID);
                $Increments = Increment::where('employee_id',$Request->EmployeeID)->get();
                $arr = array('Increments' => $Increments , 'Employee' => $Employee);
                return view('financial/management_financial_employees/Increments',$arr);
            }
            else
            {
                $newIncrement = new Increment();
                $newIncrement -> date = $Request->input('date');
                $newIncrement -> amount = $Request->input('amount');
                $newIncrement -> notes = $Request->input('notes');
                $newIncrement -> employee_id = $Request->input('employee_id');
                $newIncrement -> salary_id = $Request->input('salary_id');
                $newIncrement -> save();

                $this->AddToLog('Added New Increment',$newIncrement->date , $newIncrement->id);

                $Request->session()->put('msgSuccess', 'The Increment has been added successfully');
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function DeleteIncrement(Request $Request)
    {
        if ($this->CheckPermission('ModifyFinancialEmployees') == true)
        {
            $deleteIncrement = Increment::find($Request->IncrementID);
            $this->AddToLog('Deleted Increment',$deleteIncrement->date , $deleteIncrement->id);
            $deleteIncrement -> delete();

            return response()->json('ok');
        }
        else
        {
            return response()->json("Access Denied");
        }

    }

   /////////////////////////////////  Loans ///////////////////////////////
    public function Loans(Request $Request)
    {
        if ($this->CheckPermission('ManagementFinancialEmployees') == true)
        {
            if ($Request->isMethod('get'))
            {
                $Employee = Employee::find($Request->EmployeeID);
                $Loans = Loan::where('employee_id',$Request->EmployeeID)->get();
                $arr = array('Loans' => $Loans , 'Employee' => $Employee);
                return view('financial/management_financial_employees/Loans',$arr);
            }
            else
            {
                $newLoan = new Loan();
                $newLoan -> date = $Request->input('date');
                $newLoan -> amount = $Request->input('amount');
                $newLoan -> notes = $Request->input('notes');
                $newLoan -> monthly = $Request->input('monthly');
                $newLoan -> employee_id = $Request->input('employee_id');
                $newLoan -> salary_id = $Request->input('salary_id');
                $newLoan -> save();

                $this->AddToLog('Added New Loan',$newLoan->date , $newLoan->id);

                $Request->session()->put('msgSuccess', 'The Loan has been added successfully');
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }

    }

    public function DeleteLoan(Request $Request)
    {
        if ($this->CheckPermission('ModifyFinancialEmployees') == true)
        {
            $deleteLoan = Loan::find($Request->LoanID);
            $this->AddToLog('Deleted Loan',$deleteLoan->date , $deleteLoan->id);
            $deleteLoan -> delete();

            return response()->json('ok');
        }
        else
        {
            return response()->json("Access Denied");
        }

    }

    //////////////////////////////////  Tasks ///////////////////////////////
    public function Tasks(Request $Request)
    {
        if ($this->CheckPermission('ManagementFinancialEmployees') == true)
        {
            if ($Request->isMethod('get'))
            {
                $Employee = Employee::find($Request->EmployeeID);
                $Tasks = Task::where('employee_id',$Request->EmployeeID)->get();
                $arr = array('Tasks' => $Tasks , 'Employee' => $Employee);
                return view('financial/management_financial_employees/Tasks',$arr);
            }
            else
            {
                $newTask = new Task();
                $newTask -> date = $Request->input('date');
                $newTask -> amount = $Request->input('amount');
                $newTask -> notes = $Request->input('notes');
                $newTask -> employee_id = $Request->input('employee_id');
                $newTask -> salary_id = $Request->input('salary_id');
                $newTask -> save();

                if (!empty($Request->file('attach'))) {
                    $file = $Request->file('attach');
                    $filename ='Task_'. $newTask->id . '.' . $file->guessClientExtension();
                    Storage::disk('local')->putFileAs("/Employees_Documents/".$Request->input('employee_id'), $file, $filename);
                    $newTask -> attach = 'app/Employees_Documents/' . $Request->input('employee_id') . '/' . $filename;
                    $newTask -> save();
                }

                $this->AddToLog('Added New Task',$newTask->date , $newTask->id);

                $Request->session()->put('msgSuccess', 'The Task has been added successfully');
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function PreviewTask(Request $Request)
    {
        if ($this->CheckPermission('ManagementFinancialEmployees') == true)
        {
            $Task = Task::find($Request->TaskID);
            $path = storage_path($Task->attach);
            $file = File::get($path);
            $type = File::mimeType($path);
            $info = pathinfo($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            $response->header("Content-Disposition", 'filename="'.$info['filename'].'.'.$info['extension'].'"');
            return $response;
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function DeleteTask(Request $Request)
    {
        if ($this->CheckPermission('ModifyFinancialEmployees') == true)
        {
            $deleteTask = Task::find($Request->TaskID);
            Storage::disk('local')->delete(substr($deleteTask->attach,4));
            $this->AddToLog('Deleted Task',$deleteTask->date , $deleteTask->id);
            $deleteTask -> delete();

            return response()->json('ok');
        }
        else
        {
            return response()->json("Access Denied");
        }

    }

    //////////////////////////////////  Payrolls & Salary ///////////////////////////////
    public function Payrolls(Request $Request)
    {
        if ($this->CheckPermission('ManagementFinancialEmployees') == true)
        {
            if ($Request->isMethod('get'))
            {
                $Employee = Employee::find($Request->EmployeeID);
                $Payrolls = Payroll::where('employee_id',$Request->EmployeeID)->get();
                $Currencies = Currencie::where('status',1)->get();
                $arr = array('Payrolls' => $Payrolls , 'Employee' => $Employee,'Currencies' => $Currencies);
                return view('financial/management_financial_employees/Payrolls',$arr);
            }
            else
            {
                $newPayroll = new Payroll();
                $newPayroll -> date = $Request->input('date');
                $newPayroll -> notes = $Request->input('notes');
                $newPayroll -> employee_id = $Request->input('employee_id');
                $newPayroll -> issued_employee_id = Auth::user()->employee->id;
                $newPayroll -> salary_id = $Request->input('salary_id');
                $newPayroll -> pdf_template_id = Pdf_template::where('type','Payroll')->where('status',1)->first()->id;
                $newPayroll -> save();

                $Month = Carbon::parse($Request->input('date'))->format('m');
                //return response()->json($Month);
                $Total = $Request->input('salary_basic');

                $Increments = Increment::where('employee_id',$Request->input('employee_id'))->whereMonth('date', '=', $Month)->whereNull('payroll_id')->get();
                foreach ($Increments as $Increment) {
                    $Increment -> payroll_id = $newPayroll->id;
                    $Increment -> save();
                    $Total = $Total + $Increment->amount;
                }

                $Tasks = Task::where('employee_id',$Request->input('employee_id'))->whereMonth('date', '=', $Month)->whereNull('payroll_id')->get();
                foreach ($Tasks as $Task) {
                    $Task -> payroll_id = $newPayroll->id;
                    $Task -> save();
                    $Total = $Total + $Task->amount;
                }

                $Deductions = Deduction::where('employee_id',$Request->input('employee_id'))->whereMonth('date', '=', $Month)->whereNull('payroll_id')->get();
                foreach ($Deductions as $Deduction) {
                    $Deduction -> payroll_id = $newPayroll->id;
                    $Deduction -> save();
                    $Total = $Total - $Deduction->amount;
                }

                $Loans = Loan::where('employee_id',$Request->input('employee_id'))->whereRaw('paid <> amount')->get();
                foreach ($Loans as $Loan) {
                    $Loan -> paid = $Loan->paid + $Loan->monthly;
                    $Loan -> save();
                    $Total = $Total - $Loan->monthly;
                    $newLoans_payroll = new Loans_payroll();
                    $newLoans_payroll -> loan_id = $Loan->id;
                    $newLoans_payroll -> payroll_id = $newPayroll->id;
                    $newLoans_payroll -> amount = $Loan->monthly;
                    $newLoans_payroll -> save();
                }

                $newPayroll -> total = $Total;
                $newPayroll -> save();

                $this->AddToLog('Added New Payroll',$newPayroll->date , $newPayroll->id);

                $Request->session()->put('msgSuccess', 'The Payroll has been added successfully');
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }

    }

    public function EmployeeSignaturePayroll(Request $Request)
    {
        if ($this->CheckPermission('ManagementFinancialEmployees') == true)
        {
            if ($Request->isMethod('post'))
            {
                $EmployeeSignaturePayroll =  Payroll::find($Request->input('payroll_id'));
                $EmployeeSignaturePayroll -> signature = 'Financial_Employees/Payrolls_Sign/Employee_'.$EmployeeSignaturePayroll->employee->id.'/Payroll_'.$EmployeeSignaturePayroll->id.'.png';
                $EmployeeSignaturePayroll -> save();

                Storage::disk('local')->putFileAs('Financial_Employees/Payrolls_Sign/Employee_'.$EmployeeSignaturePayroll->employee->id.'/', $Request->image ,'Payroll_'.$EmployeeSignaturePayroll->id.'.png');

                $this->AddToLog('Employee Signature',$EmployeeSignaturePayroll->employee->first_name.' '.$EmployeeSignaturePayroll->employee->middle_name.' '.$EmployeeSignaturePayroll->employee->last_name , $EmployeeSignaturePayroll->id);

                //return redirect('/ReceivedDevices');
                $Request->session()->put('msgSuccess', 'Employee Signature successfully');
            }
            else
            {
                $Payroll = Payroll::find($Request->PayrollID);
                $arr = array('Payroll' => $Payroll);
                return view('financial/management_financial_employees/EmployeeSignature',$arr);
            }

        }
        else
        {
            return view('errors/AccessDenied');
        }

    }

    public function DeletePayroll(Request $Request)
    {
        if ($this->CheckPermission('ModifyFinancialEmployees') == true)
        {
            $deletePayroll = Payroll::find($Request->PayrollID);

            foreach ($deletePayroll->loans_payrolls as $loans_payroll) {

                $loans_payroll->loan-> paid = $loans_payroll->loan->paid - $loans_payroll->loan->monthly;
                $loans_payroll->loan -> save();
            }

            $this->AddToLog('Deleted Payroll',$deletePayroll->date , $deletePayroll->id);
            $deletePayroll -> delete();



            return response()->json('ok');
        }
        else
        {
            return response()->json("Access Denied");
        }

    }

    public function AddCurrencyEmployee(Request $Request)
    {
        if ($this->CheckPermission('ModifyFinancialEmployees') == true)
        {
            $EditEmployeeCurrency = Employee::find($Request->input('employee_id2'));
            $EditEmployeeCurrency -> currencies_id  = $Request->input('currency');
            $EditEmployeeCurrency -> save();
            return response()->json("ok");
        }
        else
        {
            return response()->json("Access Denied");
        }
    }

    public function ChangeEmpSalary(Request $Request)
    {
        if ($this->CheckPermission('ChangeSalary') == true)
        {
            $Edit = Salarie::where([['employee_id', $Request->Employee_id],['end_date', null]])->first();
            $Edit -> end_date = Carbon::now();
            $Edit -> save();
            $NewSalary = new Salarie();
            $NewSalary -> basic = $Request->salary;
            $NewSalary -> employee_id = $Request->Employee_id;
            $NewSalary -> start_date = Carbon::now();
            $NewSalary -> save();
        }
        else
        {
            return view('errors/404');
        }
    }

}
