<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Auth;

Auth::logout();
flash('success', 'You have been logged out successfully.');
redirect('index.php');
