<?php

namespace App\Http\Livewire;

use App\Actions\DeleteOptions;
use App\Actions\DeleteQuestions;
use App\Events\TestUpdated;
use App\Http\Controllers\TestsGroupsController;
use App\Models\test;
use App\Models\question;
use App\Models\option;
use App\Models\answer;
use App\Models\given_answer;
use App\Models\group;
use App\Services\TestService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Alert;

class ExamTaskEdit extends Component
{
    public $deletedQuestions = [];
    public $deletedOptions = [];
    public $type;
    public $course;
    public $questions;
    public $testTitle;
    public $testAttempts;
    public $durationMinute;
    public $resultsViewable;
    public $testId;

    protected $rules = [
        'testTitle' => 'required|string|min:5|max:40',
        'testAttempts'=>'required|integer|min:1|max:5',
        'durationMinute'=>'required|integer|min:5|max:120',
        'questions'=>'required',
        'questions.*.options'=>'required',
        'questions.*.text' => 'required|min:5|max:70',
        'questions.*.type'=>'required',
        'questions.*.right_option_index' => 'required_if:questions.*.type,TrueFalse|required_if:questions.*.type,OneChoice',
        'questions.*.options.*.text' => 'exclude_if:questions.*.type,TrueFalse|required|min:5|max:70',
    ];

    protected $messages = [
        'testTitle.required' => 'A teszt címének megadása kötelező!',
        'testTitle.min'=>'A teszt címének hossza legalább 5 karakter kell, hogy legyen!',
        'testTitle.max'=>'A teszt címének hossza legfeljebb 40 karakter lehet!',

        'testAttempts.required' => 'A  próbálkozások számanak megadása kötelező!',
        'testAttempts.min'=>'A lehetséges kitöltések száma nem lehet 1-nél kisebb!',
        'testAttempts.max'=>'A lehetséges kitöltések száma nem lehet 5-nél nagyobb!',
        'testAttempts.integer'=>'A lehetséges kitöltések számának formátuma nem megfelelő! Elvárt érték: szám',

        'durationMinute.required' => 'A rendelkezésre álló idő megadása kötelező!',
        'durationMinute.min'=>'A rendelkezésre álló idő nem lehet 5 percnél kevesebb!',
        'durationMinute.max'=>'A rendelkezésre álló idő nem lehet 120 percnél hosszabb!',
        'durationMinute.integer'=>'A rendelkezésre álló idő formátuma nem megfelelő! Elvárt érték: szám',

        'questions.*.text.required' => 'A kérdés szövegének megadása kötelező!',
        'questions.*.text.min'=>'A kérdés szövegének hossza legalább 5 karakter kell, hogy legyen!',
        'questions.*.text.max'=>'A kérdés szövegének hossza legfeljebb 70 karakter lehet!',
        'questions.*.type'=>'A kérdés típusának megadása kötelező!',
        'questions.*.right_option_index.required_if' => 'A kérdéshez még nem tartozik megoldás!',
        'questions.*.options' => 'Még nincs felvéve válaszlehetőség a kérdéshez!',

        'questions.*.options.*.text.required' => 'A válaszlehetőség szövege nem lehet üres!',
        'questions.*.options.*.text.min' => 'A válaszlehetőség szövege legalább 5 karakter hosszúnak kell lennie!',
        'questions.*.options.*.text.max' => 'A válaszlehetőség szövege legfeljebb 70 karakter hosszú lehet!',
    ];


    public function mount($testLiveWire, $course, $type="test")
    {
        $this->type = $type;
        if($type == 'test')
        {
            $this->testAttempts = $testLiveWire['maxAttempts'];
            $this->durationMinute = $testLiveWire['duration'];
            $this->resultsViewable = $testLiveWire['resultsViewable'];
        }
        else {

            $this->testAttempts = 1;
            $this->durationMinute = 30;
            $this->resultsViewable = 1;
        }
        $this->course = $course;
        $this->testId = $testLiveWire['id'];
        $this->questions = $testLiveWire['questions'];
        $this->testTitle = $testLiveWire['title'];
    }

    protected $listeners = ['questionTypeChanged'];

    public function questionTypeChanged($questionIndex)
    {
        if ($this->questions[$questionIndex]['type'] == 'TrueFalse')
        {
            foreach($this->questions[$questionIndex]['options'] as $optionIndex => $option) {
                if(array_key_exists('id', $option)) {
                    array_push($this->deletedOptions, $this->questions[$questionIndex]['options'][$optionIndex]);
                }
            }
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
        }
    }

    public function Add_Question()
    {
        array_unshift($this->questions,
            [
                'text' => '',
                'type' => '',
                'options' => [],
                'right_answer_index' => '',
            ]);
    }

    public function Remove_Question($questionIndex)
    {
        if (array_key_exists('id', $this->questions[$questionIndex])) {
            array_push($this->deletedQuestions, $this->questions[$questionIndex]);
        }
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
            'text' => '',
            'solution' => $solution,
            'score' => $score
        ]);
    }

    public function Remove_Option($questionIndex, $optionIndex)
    {
        if (array_key_exists('id', $this->questions[$questionIndex]['options'][$optionIndex])) {
            array_push($this->deletedOptions, $this->questions[$questionIndex]['options'][$optionIndex]);
        }
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function Save_Test()
    {
        $this->validate();
        $test = test::find($this->testId);
        if($this->type == "test")
        {
            $test->maxAttempts = $this->testAttempts;
            $test->duration = $this->durationMinute;
            $test->resultsViewable = (bool)($this->resultsViewable);
            $test->title = $this->testTitle;
            $test->save();
            (new TestService())->update($test, $this->questions);
            (new DeleteQuestions())->delete($this->deletedQuestions);
            (new DeleteOptions())->delete($this->deletedOptions);
            Alert::success('A teszt módosítása sikeresen megtörtént!');
            return redirect()->route('test.index', $this->course);
        }
        else
        {
            $test->maxAttempts = -1;
            $test->duration = -1;
            $test->resultsViewable = 1;
            $test->title = $this->testTitle;
            $test->save();
            (new TestService())->update($test, $this->questions);
            (new DeleteQuestions())->delete($this->deletedQuestions);
            (new DeleteOptions())->delete($this->deletedOptions);
            Alert::success('A teszt módosítása sikeresen megtörtént!');
            return redirect()->route('quizzes.index', $this->course);
        }

    }

    public function render()
    {
        return view('livewire.exam-task-edit');
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
