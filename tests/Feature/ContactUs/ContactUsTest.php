<?php

namespace Anacreation\Cms\Tests\Feature\Design;

use Anacreation\Cms\Mailables\ContactUsMailable;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Services\SettingService;
use Anacreation\Cms\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContactUsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function send_email() {
        Mail::fake();

        $settingEmail = 'testing@gmail.com';
        DB::table(SettingService::tableName)->insert([
                                                         'label' => 'contact email',
                                                         'key'   => 'contact_us_email',
                                                         'value' => $settingEmail,
                                                     ]);

        $page = factory(Page::class)->create([
                                                 'template' => 'contact_us',
                                             ]);

        $data = [
            'name'    => 'abc',
            'email'   => 'abc@gmail.com',
            'message' => 'this is simple msg',
        ];

        $this->post('/modules/'.$page->id.'/contact_us/send')
             ->assertRedirect();

        Mail::assertSent(ContactUsMailable::class,
            function($mail) use ($settingEmail) {
                return $mail->hasTo($settingEmail);
            });

    }
}
