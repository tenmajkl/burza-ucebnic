<?php

declare(strict_types=1);

return [
    'name' => 'Textbook market',
    'state-any' => 'All',
    'state-new' => 'New',
    'state-used' => 'Used',
    'state-damaged' => 'Damaged',

    'get-back' => 'Back',

    'current_year' => date('Y'),
    'currency' => 'CZK',

    'year' => 'Year',
    'offers-error' => 'Offers do not exist',
    'title' => 'Book Exchange',

    'admin' => 'Administration',
    'market' => 'Exchange',

    'cookies-banner' => 'This website uses cookies that are essential for the application to function.',
    'cookies-accept' => 'Got it!',
    'cookies-more' => 'Learn more',
    'cookies-header' => 'The website stores the following cookies:',
    'cookies-note' => 'All stored cookies are necessary for the secure functionality of the website.',
    'cookies-title' => 'Stored cookies',
    'cookies-session' => 'Maintains context when sending requests.',
    'cookies-csrf' => 'Protection against CSRF attacks.',
    'cookies-accepted' => 'Information on whether the user accepted cookies.',

    'personal-info-title' => 'Personal Data Processing Policy',
    'personal-info-list'=> 'We process the following data:',
    'personal-info-email' => 'email address',
    'personal-info-school-year' => 'school and year you attend',
    'personal-info-ip' => 'IP address',
    'personal-info-pm' => 'private messages when arranging a pickup',
    'personal-info-email-access' => 'Your email address and school are accessible to all registered buyers from your school (only if you are selling something). Additionally, school administrators also have access.',
    'personal-info-year-access' => 'Only your school administrators have access to your year.',
    'personal-info-all-access' => 'Server administrators have access to all personal data.',

    // About
    'about-title' => 'About the project',
    'about-content' => 'This project aims to digitize the physical book market that takes place in high schools through a web application, where students can post both offers and requests. The application is designed to be used independently by multiple schools. The project is also part of Michal Křipač\'s SOČ.',
    'about-thanks' => 'Thanks',
    'about-classmates' => 'Classmates who are or were also involved in SOČ',

    // Feedback
    'feedback-title' => 'Feedback',
    'feedback-content' => 'Tell us what you would like to improve or what you don\'t like. Thank you for all feedback.',
    'send' => 'Send',

    // School Registration
    'school-registration-title' => 'School Registration',
    'admin-email' => 'Administrator\'s Email',
    'school-name' => 'School Name',

    // Menu
    'offers' => 'Offers',
    'reservations' => 'Reservations',
    'wishlist' => 'Wishlist',
    'add' => 'Add',
    'my-offers' => 'My Offers',
    'notifications' => 'Notifications',
    'profile' => 'Profile',

    // Offers

    'offers-title' => 'Current Offers',
    'reserve' => 'RESERVE',
    'rating' => 'User rating @%arg',
    'no-offers' => 'Your year can\'t buy books.',
    'number-of-reservations' => 'Number of reservations',

    // Filters

    'subject' => 'Subject',
    'book-state' => 'Book condition',
    'sorting' => 'Sorting',
    'offer-state' => 'Filter by offer status',
    'offer-state-free' => 'Without reserved',
    'offer-state-reserved' => 'Including reserved',

    // Sorting

    'sorting-newest' => 'Newest',
    'sorting-oldest' => 'Oldest',
    'sorting-cheapest' => 'Cheapest',
    'sorting-most-expensive' => 'Most expensive',
    'sorting-best-reviews' => 'Best reviews',
    'sorting-worst-reviews' => 'Worst reviews',
    'sorting-optimal' => 'Recommended',

    // Reservations

    'reservations-title' => 'My Reservations',
    'qr-info' => 'Show this QR code to the seller and verify that the order is yours',
    'order-acceptance' => 'Order Acceptance',
    'order-acceptance-fail' => 'The scanned order does not exist or you do not have access to it.',
    'order-acceptance-unauthorized' => 'You need to log in to view the order.',
    'reserved-by' => 'Reserved by user',
    'order-accept' => 'Accepted',
    'order-deny' => 'Denied',
    'reserved' => 'RESERVED',

    // Wishlist

    'wishlist-title' => 'Wishlist',
    'search' => 'Search',
    'remove' => 'Remove',
    'wishlist-select-title' => 'Select books for the wishlist',
    'wishlist-max-price' => 'Maximum price',
    'wishlist-empty' => 'The wishlist is empty',

    // Add

    'add-title' => 'Create offer',
    'add-button' => 'CREATE',
    'price' => 'Price',
    'select' => 'Select',
    'selected' => 'Selected',
    'missing-image' => 'Image missing',
    'image-error' => 'Image error',
    'average-price' => 'Average price',
    'average-max-price' => 'Average maximum price',
    'book' => 'Book',
    'photo' => 'Photo',

    // My Offers

    'my-offers-title' => 'My Offers',
    // to hell with morphology (just kidding, morphology is nice)
    'interested-1' => 'interested person',
    'interested-2-3' => 'interested persons',
    'interested' => 'interested persons',
    'my-offers-none' => 'You have no offers',

    // Notifications

    'notifications-title' => 'Notifications',
    'notifications-unsupported' => 'This notification cannot be displayed!',
    'notification-wishlist' => 'Someone is offering the book "%arg"!',
    'notification-rating' => 'How satisfied were you with the seller @%arg?',
    'notification-active-reservation' => 'The reservation for the book "%arg" is now active!',
    'notification-new-reservation' => 'New reservation for the book %arg!',

    // Emojis

    'emoji-wishlist' => '🎉',
    'emoji-rating' => '📊',
    'emoji-active-reservation' => '✨',
    'emoji-new-reservation' => '🤑',

    // Profile

    'profile-title' => 'Profile',
    'profile-logout' => 'Log out',
    'profile-change-password' => 'Change password',
    'profile-old-password' => 'Old password',
    'profile-new-password' => 'New password',
];
