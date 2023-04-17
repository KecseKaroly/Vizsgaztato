 public function delete(User $user, Test $test)
    {
        return $user->id === $test->creator_id
            ? Response::allow()
            : Response::deny('Nem engedelyezett muvelet! Nem Onhoz tartozik ez a teszt!');
    }