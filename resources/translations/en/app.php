<?php

declare(strict_types=1);

return [
    'name' => 'Textbook market',
    'state-any' => 'All',
    'state-new' => 'New',
    'state-covered' => 'Covered',
    'state-damaged' => 'Damaged',

    'get-back' => 'Back',

    'compulsory' => 'Required field*',

    'current_year' => date('Y'),
    'currency' => 'CZK',

    'year' => 'Year',
    'offers-error' => 'No offers available',
    'title' => 'Textbook Exchange',

    'admin' => 'Administration',
    'market' => 'Exchange',
    'about' => 'About',

    'days' => ' days ',
    'hours' => ' hours ',
    'minutes' => ' minutes ',
    'seconds' => ' seconds ',
    'starting' => 'Starting in',
    'how' => 'How does it work?',
    'register' => 'Register with your school email',
    'make-offer' => 'Offer textbooks to younger students',
    'add-wishlist' => 'Choose the textbooks you are interested in',
    'find-offer' => 'Reserve one of the offered textbooks',
    'make-deal' => 'Agree on the handover',
    'meet' => 'Exchange textbooks at school',
    'more' => 'Learn more about the project',

    'cookies-banner' => 'This website uses cookies that are essential for the functioning of the application.',
    'cookies-accept' => 'Got it!',
    'cookies-more' => 'Learn more',
    'cookies-header' => 'The website stores the following cookies:',
    'cookies-note' => 'All stored cookies are necessary for the secure functionality of the website.', 
    'cookies-title' => 'Stored cookies',
    'cookies-session' => 'Maintains context when sending requests.',
    'cookies-csrf' => 'Protection against CSRF attacks.',
    'cookies-accepted' => 'Information whether the user accepted cookies.',

    'personal-info-title' => 'Privacy Policy',
    'personal-info' => 'I consent to the processing of the above personal data by the data controller in accordance with Articles 13 and 14 of the European Parliament and Council Regulation (EU) 2016/679 of 27 April 2016 on the protection of natural persons with regard to the processing of personal data and on the free movement of such data, and repealing Directive 95/46/EC (General Data Protection Regulation). Martin Pecl. The user\'s email address and school are processed for authentication purposes. The IP address is stored for logging purposes and is not directly associated with a specific user. Private messages available within reservations are stored only for the duration of the reservation to facilitate user communication regarding the textbook exchange.',

    // Feedback
    'feedback-title' => 'Feedback',
    'feedback-content' => 'Let us know what you would like to improve or what you do not like. Thank you for any feedback.',
    'send' => 'Send',

    // School Registration
    'school-registration-title' => 'School Registration',
    'admin-email' => 'Admin Email',
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
    'no-offers' => 'No textbooks are available for purchase for your year.',
    '1-reservation' => 'reservation',
    '5-reservations' => 'reservation',
    'photo-book' => 'Textbook photo',

    // Filters
    'subject' => 'Subject',
    'all-subjects' => 'All subjects',
    'book-state' => 'Textbook condition',
    'sorting' => 'Sorting',
    'offer-state' => 'Filter by offer status',
    'offer-state-free' => 'Without reservations',
    'offer-state-reserved' => 'Including reserved',

    // Sorting
    'sorting-newest' => 'Newest',
    'sorting-oldest' => 'Oldest',
    'sorting-cheapest' => 'Cheapest',
    'sorting-most-expensive' => 'Most Expensive',
    'sorting-best-reviews' => 'Best Reviews',
    'sorting-worst-reviews' => 'Worst Reviews',
    'sorting-optimal' => 'Recommended',

    // Reservations
    'reservations-title' => 'My Reservations',
    'qr-info' => 'Show this QR code to the seller to verify that the order is yours',
    'order-acceptance' => 'Order Receipt',
    'order-acceptance-fail' => 'The scanned order does not exist or you do not have access to it.',
    'order-acceptance-unauthorized' => 'You must be logged in to view the order.',
    'reserved-by' => 'Reserved by user',
    'order-accept' => 'Handed over',
    'order-deny' => 'Not handed over',
    'reserved' => 'RESERVED',
    'no-reservations' => 'You have no reserved textbooks.',
    'show-offers' => 'Show offers', 

    // Wishlist
    'wishlist-title' => 'Wishlist',
    'search' => 'Search',
    'remove' => 'Remove',
    'wishlist-select-title' => 'Select textbooks for the wishlist',
    'wishlist-max-price' => 'Maximum price',
    'wishlist-empty' => 'Your wishlist is empty, choose the textbooks you are interested in below.',
    'wishlist-added' => 'Textbook added to wishlist, you will be notified when it becomes available.',
    'no-wishlist' => 'There are no textbooks available for your year that you can add to the wishlist',

    // Add
    'add-title' => 'Create Offer',
    'add-button' => 'CREATE',
    'price' => 'Price',
    'select' => 'Select',
    'selected' => 'Selected',
    'missing-image' => 'Image missing',
    'image-error' => 'Image error',
    'average-price' => 'Average price',
    'average-max-price' => 'Average maximum price',
    'book' => 'Textbook',
    'photo' => 'Photo',

    // My Offers
    'my-offers-title' => 'My Offers',
    'interested-1' => 'interested person',
    'interested-2-3' => 'interested people',
    'interested' => 'interested people',
    'my-offers-none' => 'You are not offering anything.',

    // Notifications
    'notifications-title' => 'Notifications',
    'notifications-unsupported' => 'This notification cannot be displayed!',
    'notification-wishlist' => 'Someone is offering the textbook "%arg"!',
    'notification-rating' => 'How satisfied were you with the seller @%arg?',
    'notification-active-reservation' => 'The reservation for the textbook "%arg" is now active!',
    'notification-new-reservation' => 'New reservation for the textbook %arg!',
    'notification-editing' => 'The offer for the textbook %arg has been edited!',
    'notification-new-message' => 'New message for offer of %arg!',
    'no-notifications' => 'You have no notifications.',

    // Emojis
    'emoji-wishlist' => '🎉',
    'emoji-rating' => '📊',
    'emoji-active-reservation' => '✨',
    'emoji-new-reservation' => '🤑',
    'emoji-editing' => '✍️',
    'emoji-new-message' => '💬',

    // Profile
    'profile-title' => 'Profile',
    'profile-logout' => 'Log out',
    'profile-change-password' => 'Change password',
    'profile-old-password' => 'Old password',
    'profile-new-password' => 'New password',
    'profile-password-changed' => 'Password changed!',
    'profile-dangerous-zone' => 'Dangerous zone',
    'profile-change-year' => 'Change year',
    'profile-delete' => 'Delete account',
    'profile-change-year-u-sure' => 'Are you sure you want to change the year? If so, please enter your password.',
    'profile-delete-u-sure' => 'Are you sure you want to delete your account? This action will irreversibly delete all your personal data. If so, please enter your password.',
    'profile-password' => 'Password',
    'profile-pick-year' => 'Select year',

    // Reporting
    'report-title' => 'Report User',
    'reason' => 'Reason',
    'report' => 'Report',

    // Chat
    'chat' => 'Chat with the first interested person'
];
