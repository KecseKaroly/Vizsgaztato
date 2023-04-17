public function up()
{
    Schema::create('group_messages', function (Blueprint $table) {
      $table->id();
      $table->string('message');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('group_id')->constrained()->onDelete('cascade');
      $table->timestamps();
    });
}