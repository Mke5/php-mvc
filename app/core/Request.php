<?php 

/**
 * Secure Request class with CSRF Protection
 * Handles GET, POST, and FILES variables safely
 */

namespace Core;

defined('ROOTPATH') OR exit('Access Denied!');

class Request
{
	public function __construct()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}

	/** Check which request method was used **/
	public function method(): string
	{
		return $_SERVER['REQUEST_METHOD'] ?? 'GET';
	}

	/** Check if data was posted **/
	public function posted(): bool
	{
		return $_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST);
	}

	/** Get a value from the POST variable **/
	public function post(string $key = '', mixed $default = ''): mixed
	{
		if (empty($key)) {
			return $this->sanitizeArray($_POST);
		}

		return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
	}

	/** Get a value from the FILES variable **/
	public function files(string $key = '', mixed $default = ''): mixed
	{
		return $key === '' ? $_FILES : ($_FILES[$key] ?? $default);
	}

	/** Get a value from the GET variable **/
	public function get(string $key = '', mixed $default = ''): mixed
	{
		if (empty($key)) {
			return $this->sanitizeArray($_GET);
		}

		return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
	}

	/** Get a value from the REQUEST variable (GET or POST) **/
	public function input(string $key, mixed $default = ''): mixed
	{
		return isset($_REQUEST[$key]) ? $this->sanitize($_REQUEST[$key]) : $default;
	}

	/** Get all values from the REQUEST variable **/
	public function all(): mixed
	{
		return $this->sanitizeArray($_REQUEST);
	}

	/** Generate a CSRF token **/
	public function generateCsrfToken(): string
	{
		if (empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}
		return $_SESSION['csrf_token'];
	}

	/** Validate the CSRF token **/
	public function validateCsrfToken(): bool
	{
		if (!$this->posted()) {
			return true; // No CSRF check needed for GET requests
		}

		$csrf_token = $this->post('csrf_token');

		if (empty($csrf_token) || !isset($_SESSION['csrf_token']) || $csrf_token !== $_SESSION['csrf_token']) {
			return false; // Invalid CSRF token
		}

		// Optional: Regenerate token after successful validation
		unset($_SESSION['csrf_token']);
		return true;
	}

	/** Sanitize input to prevent XSS **/
	private function sanitize(mixed $value): mixed
	{
		if (is_array($value)) {
			return $this->sanitizeArray($value);
		}
		return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	}

	/** Sanitize an entire array **/
	private function sanitizeArray(array $array): array
	{
		return array_map([$this, 'sanitize'], $array);
	}

	// In app/Core/Request.php

	public function validate_csrf(): bool
	{
		$session = new \Core\Session();
		$token = $this->post('csrf_token');

		return $session->validate_csrf($token);
	}

}
