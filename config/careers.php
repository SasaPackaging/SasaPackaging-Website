<?php

/*
 | Careers form settings.
 | - education_levels : key => label shown in the dropdown
 | - profiles         : extra questions per job "profile" (set on each opening).
 |                      type = text | url | textarea | select | checkboxes | yesno
 */
return [
    'education_levels' => [
        'matric' => 'Matric / O-Level',
        'intermediate' => 'Intermediate / A-Level',
        'diploma' => 'Diploma / DAE',
        'bachelors' => "Bachelor's (Graduate)",
        'masters' => "Master's",
        'mphil' => 'MPhil',
        'phd' => 'PhD',
    ],
    'experience_years' => ['Less than 1 year', '1-2 years', '3-5 years', '6-10 years', '10+ years'],

    'profiles' => [
        'general' => [
            ['key' => 'strength', 'label' => 'What would you bring to the team?', 'type' => 'textarea', 'required' => false],
        ],
        'creative' => [
            ['key' => 'portfolio', 'label' => 'Portfolio link', 'type' => 'url', 'required' => true, 'placeholder' => 'https://'],
            [
                'key' => 'software',
                'label' => 'Design software you use',
                'type' => 'checkboxes',
                'required' => true,
                'options' => ['Illustrator', 'Photoshop', 'CorelDRAW', 'InDesign', 'ArtiosCAD']
            ],
            ['key' => 'dielines', 'label' => 'Have you prepared packaging dielines before?', 'type' => 'yesno', 'required' => true],
        ],
        'production' => [
            [
                'key' => 'machines',
                'label' => 'Machines you have worked on',
                'type' => 'checkboxes',
                'required' => true,
                'options' => ['Die cutting', 'Lamination', 'Paper pasting', 'Foil stamping', 'Offset printing', 'Rigid box assembling']
            ],
            [
                'key' => 'shift',
                'label' => 'Shift you can work',
                'type' => 'select',
                'required' => true,
                'options' => ['Day', 'Night', 'Rotating']
            ],
        ],
        'sales' => [
            [
                'key' => 'languages',
                'label' => 'Languages you can work in',
                'type' => 'checkboxes',
                'required' => true,
                'options' => ['English', 'Urdu', 'Punjabi', 'Arabic']
            ],
            ['key' => 'export', 'label' => 'Have you handled export clients?', 'type' => 'yesno', 'required' => true],
        ],
    ],
];
