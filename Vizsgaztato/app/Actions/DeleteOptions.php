<?php

namespace App\Actions;

use App\Models\option;

class DeleteOptions {
    public function delete($options): void
    {
        foreach ($options as $option) {
            $option = option::find($option['id'])->delete();
        }
    }
}
