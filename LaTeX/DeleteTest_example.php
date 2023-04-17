public function destroy(Test $test) {
        try{
            $this->authorize('delete', $test);
            $test->delete();
            Alert::success('A vizsgasor sikeresen torolve!');
            return back();
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }

    }