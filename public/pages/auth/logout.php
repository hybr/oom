<?php
require_once __DIR__ . '/../../../bootstrap.php';

auth()->logout();

success('You have been logged out successfully.');
redirect('/');
