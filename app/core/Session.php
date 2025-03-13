<?php

/**
 * Secure Session class with enhanced protection & CSRF support
 */

namespace Core;

defined('ROOTPATH') OR exit('Access Denied!');

class Session
{
	private string $mainkey = 'APP';
	private string $userkey = 'USER';
	private string $csrfkey = 'CSRF_TOKEN';

	/** Activate session if not yet started **/
	private function start_session(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start([
				'use_strict_mode' => true,
				'use_only_cookies' => true,
				'cookie_httponly' => true,
				'cookie_secure' => isset($_SERVER['HTTPS']),
				'cookie_samesite' => 'Strict'
			]);

			// Regenerate session ID for security
			if (!isset($_SESSION['INITIATED'])) {
				session_regenerate_id(true);
				$_SESSION['INITIATED'] = true;
			}
		}
	}

	/** Store data in session **/
	public function set(mixed $keyOrArray, mixed $value = ''): void
	{
		$this->start_session();

		if (is_array($keyOrArray)) {
			foreach ($keyOrArray as $key => $val) {
				$_SESSION[$this->mainkey][$key] = $val;
			}
		} else {
			$_SESSION[$this->mainkey][$keyOrArray] = $value;
		}
	}

	/** Retrieve data from session **/
	public function get(string $key, mixed $default = ''): mixed
	{
		$this->start_session();
		return $_SESSION[$this->mainkey][$key] ?? $default;
	}

	/** Store authenticated user session **/
	public function auth(mixed $user_row): void
	{
		$this->start_session();
		$_SESSION[$this->userkey] = $user_row;
	}

	/** Remove authenticated user session **/
	public function logout(): void
	{
		$this->start_session();
		unset($_SESSION[$this->userkey]);
		session_destroy();
	}

	/** Check if user is logged in **/
	public function is_logged_in(): bool
	{
		$this->start_session();
		return isset($_SESSION[$this->userkey]);
	}

	/** Get user session data **/
	public function user(string $key = '', mixed $default = ''): mixed
	{
		$this->start_session();

		if ($key === '' && isset($_SESSION[$this->userkey])) {
			return $_SESSION[$this->userkey];
		}

		return $_SESSION[$this->userkey][$key] ?? $default;
	}

	/** Retrieve and delete a session key **/
	public function pop(string $key, mixed $default = ''): mixed
	{
		$this->start_session();
		if (isset($_SESSION[$this->mainkey][$key])) {
			$value = $_SESSION[$this->mainkey][$key];
			unset($_SESSION[$this->mainkey][$key]);
			return $value;
		}

		return $default;
	}

	/** Retrieve all session data **/
	public function all(): array
	{
		$this->start_session();
		return $_SESSION[$this->mainkey] ?? [];
	}

	/** Generate and store CSRF token **/
	public function generateCsrfToken(): string
	{
		$this->start_session();
		if (!isset($_SESSION[$this->csrfkey])) {
			$_SESSION[$this->csrfkey] = bin2hex(random_bytes(32));
		}
		return $_SESSION[$this->csrfkey];
	}

	// In app/Core/Session.php

	public function csrf_token()
	{
		$this->start_session();
		
		if (empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}

		return $_SESSION['csrf_token'];
	}

	public function validate_csrf($token)
	{
		$this->start_session();

		if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
			// Regenerate the token after validation for extra security
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
			return true;
		}

		return false;
	}

}
