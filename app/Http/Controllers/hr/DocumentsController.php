<?php

namespace App\Http\Controllers\hr;

use Controller, Auth, Storage, Response, File;
use Illuminate\Http\Request;
use App\Models\{Employee,Education,Experience,Legal_doc,Emp_doc,Training,Countrie};

class DocumentsController extends Controller
{
///////////////////////////////////////////// Legal Documents //////////////////////////////////
    public function LegalDocs(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            if ($Request->isMethod('post'))
            {
                $NewDoc = new Legal_doc();
                $NewDoc -> doc_type = $Request->input('doc_type');
                $NewDoc -> start_vaild = $Request->input('start_vaild');
                $NewDoc -> end_valid = $Request->input('end_valid');
                $NewDoc -> employee_id = $Request->input('employee_id');
                $NewDoc -> save();

                $file = $Request->file('legal_attach');
                $filename ='Legal_'. $NewDoc->id . '.' . $file->guessClientExtension();
                Storage::disk('local')->putFileAs("/Employees_Documents/".$Request->input('employee_id'), $file, $filename);
                $NewDoc -> legal_attach = 'app/Employees_Documents/' . $Request->input('employee_id') . '/' . $filename;
                $NewDoc -> save();

                $this->AddToLog('Added Legal Doc',$NewDoc->id , $NewDoc->employee_id);
            }
            else
            {
                $legal_docs = Legal_doc::where('employee_id',$Request->idEmployee)->get();
                $Employee = Employee::find($Request->idEmployee);
                $arr = array('legal_docs' => $legal_docs , 'Employee' => $Employee);
                return view('hr/Documents/LegalDocs',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function DeleteLegalDocs(Request $Request)
    {
        $LegalDoc = Legal_doc::find($Request->LegalDocID);
        Storage::disk('local')->delete(substr($LegalDoc->legal_attach,4));
        $this->AddToLog('Deleted Legal Doc',$LegalDoc->id , $LegalDoc->employee->first_name .' ' . $LegalDoc->employee->last_name);
        $LegalDoc -> delete();

        return response()->json('OK');
    }

    public function PreviewLegalDoc(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            $Legal_doc = Legal_doc::find($Request->LegalID);
            $path = storage_path($Legal_doc->legal_attach);
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

///////////////////////////////////////////// Training //////////////////////////////////
    public function TrainingDocs(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            if ($Request->isMethod('post'))
            {
                $NewDoc = new Training();
                $NewDoc -> institute = $Request->input('institute');
                $NewDoc -> course = $Request->input('course');
                $NewDoc -> result = $Request->input('result');
                $NewDoc -> course_loc = $Request->input('course_loc');
                $NewDoc -> issue_cer = $Request->input('issue_cer');
                $NewDoc -> employee_id = $Request->input('employee_id');
                $NewDoc -> save();

                $file = $Request->file('train_cer');
                $filename ='Training_'. $NewDoc->id . '.' . $file->guessClientExtension();
                Storage::disk('local')->putFileAs("/Employees_Documents/".$Request->input('employee_id'), $file, $filename);
                $NewDoc -> train_cer = 'app/Employees_Documents/' . $Request->input('employee_id') . '/' . $filename;
                $NewDoc -> save();

                $this->AddToLog('Added Training Doc',$NewDoc->id , $NewDoc->employee_id);
            }
            else
            {
                $Training_docs = Training::where('employee_id',$Request->idEmployee)->get();
                $Employee = Employee::find($Request->idEmployee);
                $Countries = Countrie::where('status',1)->get();
                $arr = array('Training_docs' => $Training_docs , 'Employee' => $Employee , 'Countries' => $Countries);
                return view('hr/Documents/TrainingDocs',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function DeleteTrainingDocs(Request $Request)
    {
        $TrainingDoc = Training::find($Request->TrainingDocID);
        Storage::disk('local')->delete(substr($TrainingDoc->train_cer,4));
        $this->AddToLog('Deleted Training Doc',$TrainingDoc->id , $TrainingDoc->employee->first_name .' ' . $TrainingDoc->employee->last_name);
        $TrainingDoc -> delete();

        return response()->json('OK');
    }

    public function PreviewTrainingDoc(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            $Training = Training::find($Request->TrainingID);
            $path = storage_path($Training->train_cer);
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

////////////////////////////// Documents /////////////////////////////////////
    public function Documents(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            if ($Request->isMethod('post'))
            {
                $NewDoc = new Emp_doc();
                $NewDoc -> docu_type = $Request->input('docu_type');
                $NewDoc -> description = $Request->input('description');
                $NewDoc -> employee_id = $Request->input('employee_id');
                $NewDoc -> save();

                $file = $Request->file('docu_attach');
                $filename ='Document_'. $NewDoc->id . '.' . $file->guessClientExtension();
                Storage::disk('local')->putFileAs("/Employees_Documents/".$Request->input('employee_id'), $file, $filename);
                $NewDoc -> docu_attach = 'app/Employees_Documents/' . $Request->input('employee_id') . '/' . $filename;
                $NewDoc -> save();

                $this->AddToLog('Added Document',$NewDoc->id , $NewDoc->employee_id);
            }
            else
            {
                $Documents = Emp_doc::where('employee_id',$Request->idEmployee)->get();
                $Employee = Employee::find($Request->idEmployee);
                $arr = array('Documents' => $Documents , 'Employee' => $Employee);
                return view('hr/Documents/Documents',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function DeleteDocuments(Request $Request)
    {
        $Document = Emp_doc::find($Request->DocumentID);
        Storage::disk('local')->delete(substr($Document->docu_attach,4));
        $this->AddToLog('Deleted Document',$Document->id , $Document->employee->first_name .' ' . $Document->employee->last_name);
        $Document -> delete();

        return response()->json('OK');
    }

    public function PreviewDocument(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            $Document = Emp_doc::find($Request->DocumentID);
            $path = storage_path($Document->docu_attach);
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

////////////////////////////// Experience /////////////////////////////////////
    public function Experience(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            if ($Request->isMethod('post'))
            {
                $newExperience = new Experience();
                $newExperience -> employer = $Request->input('employer');
                $newExperience -> sector = $Request->input('sector');
                $newExperience -> job_title = $Request->input('job_title');
                $newExperience -> start_date = $Request->input('start_date');
                $newExperience -> end_date = $Request->input('end_date');
                $newExperience -> job_loc = $Request->input('job_loc');
                $newExperience -> employee_id = $Request->input('employee_id');
                $newExperience -> save();
            }
            else
            {
                $Experiences = Experience::where('employee_id',$Request->idEmployee)->get();
                $Countries = Countrie::where('status',1)->get();
                $Employee = Employee::find($Request->idEmployee);
                $arr = array('Experiences' => $Experiences , 'Employee' => $Employee , 'Countries' => $Countries,);
                return view('hr/Documents/Experiences',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function DeleteExperience(Request $Request)
    {
        $Experience = Experience::find($Request->ExperienceID);
        $Experience -> delete();
        return response()->json('OK');
    }

////////////////////////////// Education /////////////////////////////////////
    public function Education(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            if ($Request->isMethod('post'))
            {
                $newEducation = new Education();
                $newEducation -> level = $Request->input('level');
                $newEducation -> school = $Request->input('school');
                $newEducation -> degree = $Request->input('degree');
                $newEducation -> field = $Request->input('field');
                $newEducation -> grade = $Request->input('grade');
                $newEducation -> start = $Request->input('start');
                $newEducation -> end = $Request->input('end');
                $newEducation -> location = $Request->input('location');
                $newEducation -> employee_id = $Request->input('employee_id');
                $newEducation -> save();
            }
            else
            {
                $Educations = Education::where('employee_id',$Request->idEmployee)->get();
                $Countries = Countrie::where('status',1)->get();
                $Employee = Employee::find($Request->idEmployee);
                $arr = array('Educations' => $Educations , 'Employee' => $Employee , 'Countries' => $Countries,);
                return view('hr/Documents/Educations',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function DeleteEducation(Request $Request)
    {
        $Education = Education::find($Request->EducationID);
        $Education -> delete();
        return response()->json('OK');
    }

}
