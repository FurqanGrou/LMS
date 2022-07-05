<?php

use Illuminate\Database\Seeder;

class AlertMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $alertMessages = [
            ['content' => 'Test 1', 'content_en' => 'Test 1', 'level' => 1],
            ['content' => 'Test 2', 'content_en' => 'Test 2', 'level' => 2],
            ['content' => 'Test 3', 'content_en' => 'Test 3', 'level' => 3],
        ];

        foreach ($alertMessages as $alertMessage){
            \App\AlertMessage::query()->create($alertMessage);
        }
    }
}
