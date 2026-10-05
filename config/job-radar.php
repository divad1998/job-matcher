<?php
return [
    // Gmail recipient.
    'to' => env('JOB_RADAR_TO', env('MAIL_FROM_ADDRESS')),

    'max_jobs_per_email' => 8,
    'min_score_to_email' => 45,
    'greenhouse' => [
        ['company' => 'Okta', 'token' => 'okta'],
        ['company' => 'Canonical', 'token' => 'canonical']
    ],
    'lever' => [
        ['company' => 'Yassir', 'site' => 'Yassir'],
        ['company' => 'Binance', 'site' => 'binance'],
        ['company' => 'Xsolla', 'site' => 'xsolla'],
        ['company' => 'Airalo', 'site' => 'airalo'],
        ['company' => 'NFQ', 'site' => 'nfq'],
        ['company' => 'SiteGround', 'site' => 'siteground'],
        ['company' => 'Jobgether', 'site' => 'jobgether'],
        ['company' => 'Yuno', 'site' => 'yuno'],
        ['company' => 'Swile', 'site' => 'swile'],
        ['company' => 'Flex', 'site' => 'Flex'],
        ['company' => 'Peak', 'site' => 'peakgames'],
        ['company' => 'Spin Technology', 'site' => 'spinai'],
        ['company' => 'Coins.ph', 'site' => 'coins'],
        ['company' => 'Very Good Security', 'site' => 'verygoodsecurity'],
        ['company' => 'Sycurio', 'site' => 'sycurio'],
        ['company' => 'BlaBlaCar', 'site' => 'blablacar'],
        ['company' => 'ScaleTech', 'site' => 'scale3c'],
    ],
    'profile' => [
        'name' => 'David',
        'years_experience' => 3,
        'target_roles' => [
            'Backend Engineer','Backend Developer','PHP Developer','Laravel Developer',
            'Java Backend Engineer','Java Developer','Spring Boot Developer',
        ],
        'skills' => [
            'PHP','Laravel','Java','Spring Boot','REST API','REST APIs','microservices',
            'MySQL','MariaDB','PostgreSQL','Redis','WebSockets','Docker','Git','GitHub',
            'AWS','queues','caching','PHPUnit','JUnit','fintech','payments','KYC','KYB',
            'NIN','BVN','Paystack','Flutterwave','wallet','wallets','virtual accounts',
            'webhooks','reconciliation',
        ],
        'preferred_locations' => [
            'remote','hybrid','nigeria','europe','united kingdom','uk','germany','netherlands',
            'ireland','france','portugal','spain','lithuania','austria','canada',
        ],
        'remote_keywords' => ['remote','work from anywhere','distributed'],
        'negative_keywords' => [
            'frontend developer','front-end developer','react developer','ios developer',
            'android developer','flutter developer','data scientist','machine learning engineer',
            'designer','product manager','sales','marketing','internship','unpaid',
        ],
        'seniority_negative_keywords' => ['staff engineer','principal engineer','distinguished engineer'],
    ],
];
