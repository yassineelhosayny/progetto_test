<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormTemplate;

class FormTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'basic_contact_form',
                'label' => 'Basic Contact Form',
                'description' => 'Simple contact form with name, email, and message fields',
                'category' => 'form',
                'schema' => [
                    'fields' => [
                        [
                            'type' => 'section',
                            'label' => 'Contact Information',
                            'fields' => [
                                [
                                    'type' => 'text',
                                    'name' => 'name',
                                    'label' => 'Full Name',
                                    'required' => true,
                                    'maxLength' => 255,
                                    'placeholder' => 'Enter your full name',
                                ],
                                [
                                    'type' => 'text',
                                    'name' => 'email',
                                    'label' => 'Email Address',
                                    'required' => true,
                                    'maxLength' => 255,
                                    'placeholder' => 'your@email.com',
                                ],
                            ]
                        ],
                        [
                            'type' => 'section',
                            'label' => 'Message',
                            'fields' => [
                                [
                                    'type' => 'textarea',
                                    'name' => 'message',
                                    'label' => 'Message',
                                    'required' => true,
                                    'rows' => 5,
                                    'maxLength' => 2000,
                                    'placeholder' => 'Please describe your inquiry...',
                                ],
                            ]
                        ]
                    ]
                ],
                'is_active' => true,
                'is_system' => true,
                'version' => '1.0.0',
                'metadata' => [
                    'author' => 'System',
                    'created_for' => 'Contact forms',
                ],
            ],
        ];

        foreach ($templates as $template) {
            FormTemplate::create($template);
        }
    }
}
