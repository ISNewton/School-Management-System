<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Classes;
use App\Models\Parents;
use App\Models\Student;
use App\Models\User;
use App\Services\FileUploadService;
use App\Services\RegisterationService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::with('class','parent')->latest()->paginate(5);

        return view("students.index", compact( 'students'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes = Classes::all();
        return view('students.create',compact('classes'));
    }

    public function store(StoreStudentRequest $request)
    {
        // //Store validated arguments into data array
        $data = $request->validated();

        /* $data["parent_id"] = RegisterationService::getStudentParent($data);
        dd($data["parent_id"]); */


        /* //Check If Student Exist
        if (Student::where("student_name", $request->student_name)->exists())
            return response()->json(["success" => false, "message" => "هذا الطالب موجود بالفعل"]);


        //Check If Parent Exist
        $parent = Parents::where("parent_name", $request->parent_name);

        if (!$parent->exists()) {
            //Store New Parent
            $parent = Parents::create($data);
        } else {
            ///Get Existing Parent
            $parent = $parent->first();
        } */
        // Check if student's parent exists in the database or create one
        $data["parent_id"] = RegisterationService::getStudentParent($data);

        //Upload Student Image And return Image name
        $data["student_photo"] = FileUploadService::handleImage($request->file("student_photo"),'student');

        ///Generate Student Account
        $data['user_id'] = RegisterationService::createUserAcount('student');

        // Store student data in students table
        $student = Student::create($data);

        ///Additional Data For Ajax
        $data["student_class"] = $student->class->class_name;
        $data["id"] = $student->id;

        return response()->json(["success" => true, "message" => "تم اضافة الطالب بنجاح", "data" => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {

        //$student->load(['user', 'class', 'parent', 'parent.students' => fn($query) => $query->where('students.id','!=',$student->id)]);
        $student->load(['user', 'class', 'parent']);
        //$siblings = $student->parent?->students;
        $siblings = $student->siblings();
        return view('students.show', compact('student','siblings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $classes = Classes::all();

        return view("students.edit", compact("classes", "student"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {


        // //Store validated arguments into data array
        $data = $request->validated();

        //Update Parent
        $parent = Parents::find($student->parent_id);
        $parent->update($data);

        ///Get Parent Id
        $data["parent_id"] = $parent->id;


        //Upload Student Image And return Image name
        $data["student_photo"] = FileUploadService::updateImage($request->file("student_photo"),$student->student_photo,'student');

        // Update student
        $student->update($data);


        ///Additional Data For Ajax
        $data["student_class"] = $student->class->class_name;
        $data["id"] = $student->id;

        return redirect()->route('students.show',$student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        FileUploadService::deleteImage($student->student_photo);

        $student->delete();

        return back()->with("success", "تم حذف الطالب بنجاح");
    }

    /**
     *
     */
    public function search($name) {
        /* str_replace(
            ['\\','%','_'],
            ['\\\\','\%','\_'],
            $name
        ); */

        $students = Student::where('student_name','LIKE','%'.$name.'%')->get();

        return json_encode([
            'students' => $students
        ]);
    }
}
