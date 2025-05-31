<?php
function runMiddleware($name)
{
  switch ($name) {
    case 'auth':
      requireAuth();
      break;
    case 'guest':
      requireGuest();
      break;
    default:
      throw new Exception("Unknown middleware: $name");
  }
}

function requireGuest()
{
  if (isset($_SESSION['user_id'])) {
    header('Location: /nutritrack/profile');
    exit();
  }
}

// Simple auth middleware
function requireAuth()
{
  if (!isset($_SESSION['user_id'])) {
    header('Location: /nutritrack/login');
    exit();
  }
}
