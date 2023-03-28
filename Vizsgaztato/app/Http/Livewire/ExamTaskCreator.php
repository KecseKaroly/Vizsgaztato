<?php

namespace App\Http\Livewire;

use App\Models\CoursesExams;
use App\Models\CoursesQuizzes;
use App\Models\test;
use App\Models\question;
use App\Models\option;
use App\Models\answer;
use App\Models\given_answer;
use App\Models\group;
use App\Services\TestService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ExamTaskCreator extends Component
{
    public $type;
    public $module_id;
    public $course;
    public $questions;
    public $testTitle;
    public $testAttempts;
    public $durationMinute;
    public $resultsViewable;

    protected $rules = [
        'testTitle' => 'required|string|min:5|max:40',
        'testAttempts' => 'required|integer|min:1|max:5',
        'durationMinute' => 'required|integer|min:5|max:120',
        'questions' => 'required',
        'questions.*.options' => 'required',
        'questions.*.text' => 'required|min:5|max:70',
        'questions.*.type' => 'required',
        'questions.*.right_option_index' => 'required_if:questions.*.type,TrueFalse|required_if:questions.*.type,OneChoice',
        'questions.*.options.*.text' => 'exclude_if:questions.*.type,TrueFalse|required|min:5|max:70',
    ];

    protected $messages = [
        'testTitle.required' => 'A teszt címének megadása kötelező!',
        'testTitle.min' => 'A teszt címének hossza legalább 5 karakter kell, hogy legyen!',
        'testTitle.max' => 'A teszt címének hossza legfeljebb 40 karakter lehet!',

        'testAttempts.required' => 'A  próbálkozások számanak megadása kötelező!',
        'testAttempts.min' => 'A lehetséges kitöltések száma nem lehet 1-nél kisebb!',
        'testAttempts.max' => 'A lehetséges kitöltések száma nem lehet 5-nél nagyobb!',
        'testAttempts.integer' => 'A lehetséges kitöltések számának formátuma nem megfelelő! Elvárt érték: szám',

        'durationMinute.required' => 'A rendelkezésre álló idő megadása kötelező!',
        'durationMinute.min' => 'A rendelkezésre álló idő nem lehet 5 percnél kevesebb!',
        'durationMinute.max' => 'A rendelkezésre álló idő nem lehet 120 percnél hosszabb!',
        'durationMinute.integer' => 'A rendelkezésre álló idő formátuma nem megfelelő! Elvárt érték: szám',

        'questions.*.text.required' => 'A kérdés szövegének megadása kötelező!',
        'questions.*.text.min' => 'A kérdés szövegének hossza legalább 5 karakter kell, hogy legyen!',
        'questions.*.text.max' => 'A kérdés szövegének hossza legfeljebb 70 karakter lehet!',
        'questions.*.type' => 'A kérdés típusának megadása kötelező!',
        'questions.*.right_option_index.required_if' => 'A kérdéshez még nem tartozik megoldás!',
        'questions.*.options' => 'Még nincs felvéve válaszlehetőség a kérdéshez!',

        'questions.*.options.*.text.required' => 'A válaszlehetőség szövege nem lehet üres!',
        'questions.*.options.*.text.min' => 'A válaszlehetőség szövege legalább 5 karakter hosszúnak kell lennie!',
        'questions.*.options.*.text.max' => 'A válaszlehetőség szövege legfeljebb 70 karakter hosszú lehet!',
    ];

    public function mount($course, $module_id = null, $type = "test")
    {
        $this->type = $type;
        $this->course = $course;
        $this->module_id = $module_id;
        $this->questions = [];
        $this->testTitle = '';
        $this->resultsViewable = true;
        if ($type == "test") {
            $this->testAttempts = 1;
            $this->durationMinute = 10;
        }
        else {
            $this->testAttempts = 5;
            $this->durationMinute = 120;
        }
    }

    protected $listeners = ['questionTypeChanged'];

    public function questionTypeChanged($questionIndex)
    {
        if ($this->questions[$questionIndex]['type'] == 'TrueFalse') {
            $this->questions[$questionIndex]['options'] = [
                [
                    'text' => 'Igaz',
                    'solution' => '',
                    'score' => 0
                ],
                [
                    'text' => 'Hamis',
                    'solution' => '',
                    'score' => 0
                ]
            ];
        } else if ($this->questions[$questionIndex]['type'] == 'Sequence') {
            $this->questions[$questionIndex]['options'] = [];
        }
    }

    public function Add_Question()
    {
        array_unshift($this->questions,
            [
                'text' => '',
                'type' => '',
                'options' => [],
                'right_option_index' => '',
            ]);
    }

    public function Remove_Question($questionIndex)
    {
        unset($this->questions[$questionIndex]);
    }

    public function Add_Option($questionIndex)
    {
        $solution = '';
        $score = 0;
        if ($this->questions[$questionIndex]["type"] == "Sequence") {
            $solution = count($this->questions[$questionIndex]['options']) + 1;
            $score = 1;
        }
        array_unshift($this->questions[$questionIndex]['options'], [
            'id' => $questionIndex . count($this->questions[$questionIndex]['options']) + 1,
            'text' => '',
            'solution' => $solution,
            'score' => $score
        ]);
    }

    public function Remove_Option($questionIndex, $optionIndex)
    {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function Save_Test()
    {
        $this->validate();
        if ($this->type == "test") {
            $test = new test;
            $test->is_exam = true;
            $test->title = $this->testTitle;
            $test->maxAttempts = $this->testAttempts;
            $test->duration = $this->durationMinute;
            $test->resultsViewable = $this->resultsViewable;
            $test->creator_id = auth()->id();
            $test->course_id = $this->course->id;
            $test->save();

            (new TestService())->store($test, $this->questions);

            Alert::success('A teszt mentése sikeresen megtörtént!');
            return redirect()->route('test.index', $this->course);
        } else if ($this->type == "quiz") {
            $test = new test;
            $test->is_exam = false;
            $test->title = $this->testTitle;
            $test->maxAttempts = -1;
            $test->duration = -1;
            $test->resultsViewable = 1;
            $test->creator_id = auth()->id();
            $test->course_id = $this->course->id;
            $test->module_id = $this->module_id;
            $test->save();

            (new TestService())->store($test, $this->questions);

            Alert::success('A kvíz mentése sikeresen megtörtént!');
            return redirect()->route('quizzes.index', $this->course);
        }
    }

    public function render()
    {
        return view('livewire.exam-task-creator');
    }

    public function updateOptionOrder($list)
    {
        $newOptions = [];
        foreach ($list as $element) {
            $indexek = explode("_", $element["value"]);
            array_push($newOptions, $this->questions[$indexek[0]]['options'][$indexek[1]]);
        }
        $this->questions[$indexek[0]]['options'] = $newOptions;
    }
}
