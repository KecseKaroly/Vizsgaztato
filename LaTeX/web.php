Route :: middleware (['auth','verified'])->group(function() {
    Route::get( '/', [HomeController::class,'index'])->name('home');
    Route::resource('groups', GroupController::class);
...
}