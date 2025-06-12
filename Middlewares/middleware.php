<?php
/**
 * Runs a specified middleware function.
 *
 * @param string $name The name of the middleware to run ('auth' or 'guest').
 * @return void
 * @throws Exception If an unknown middleware name is provided.
 */
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

/**
 * Middleware to ensure the user is a guest (not logged in).
 * Redirects to the profile page if a user is logged in.
 *
 * @return void
 */
function requireGuest()
{
  if (isset($_SESSION['user_id'])) {
    header('Location: /nutritrack/profile');
    exit();
  }
}

/**
 * Simple authentication middleware.
 * Ensures the user is logged in. Redirects to the login page if not authenticated.
 *
 * @return void
 */
function requireAuth()
{
  if (!isset($_SESSION['user_id'])) {
    header('Location: /nutritrack/login');
    exit();
  }
}
