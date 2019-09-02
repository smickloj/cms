<?php

/**
 *	====================
 * 	Error handler class.
 *	====================
 */

class error_handler extends Exception {

	/**
	 * The error message.
	 *
	 * @access protected
	 * @var string The error message to display.
	 */
	protected $message;

	/**
	 * Error fatality.
	 *
	 * @access private
	 * @var boolean Whether or not to exit on this error.
	 */
	private $execute;

	/**
	 * The heading.
	 *
	 * @access private
	 * @var string The heading to display.
	 */
	private $heading;

	/**
	 * The constructor; setter for error properties.
	 *
	 * @access public
	 * @param string $message The error message to display.
	 * @param boolean $execute Whether or not to exit.
	 * @param string $heading The heading to display.
	 */
	public function __construct($message, $execute = true, $heading = '') {
		$this->set_message($message);
		$this->set_execute($execute);
		$this->set_heading($heading);
	}

	/**
	 * Setter for the error message.
	 *
	 * @access private
	 * @param string | object $message The error message to display.
	 */
	private function set_message($message) {
		if(is_string($message) && !empty($message)) $this->message = $message;
		elseif(is_object($message) === true) $this->message = $message->getMessage();
		else $this->message = 'An unknown error has occurred.';
	}

	/**
	 * Setter for the execution property.
	 *
	 * @access private
	 * @param boolean $execute Whether or not to exit.
	 */
	private function set_execute($execute) {
		if(is_bool($execute) === true) $this->execute = $execute;
		else $this->execute = true; 
	}

	/**
	 * Setter for the heading.
	 *
	 * @access private
	 * @param string $heading The heading to display.
	 */
	private function set_heading($heading) {
		if(isset($heading) && !empty($heading)) $this->heading = $heading;
		else $this->heading = 'Error';
	}

	/**
	 * Get the error message.
	 *
	 * @access protected
	 * @return string The error message, inherited from the base Exception class.
	 */
	protected function error_message() {
		return parent::getMessage();
	}

	/**
	 * Internal and runtime error handler.
	 *
	 * @static
	 * @access public
	 * @param int $code The error code.
	 * @param string $message The error message.
	 * @param string $file The name of the file in which the error occurred.
	 * @param int $line The line number in the file that triggered the error.
	 */
	public static function handle_error($code, $message, $file, $line) {
		if(!(error_reporting() & $code)) return false;

		if(in_array($code, array(E_WARNING, E_USER_WARNING))) 
			$type = E_USER_WARNING;
		elseif(in_array($code, array(E_NOTICE, E_USER_NOTICE))) 
			$type = E_USER_NOTICE;
		else 
			$type = E_USER_ERROR;

		trigger_error($message, $type);

		return true;
	}

	/**
	 * Print the error.
	 *
	 * @access public
	 */
	public function Print() { ?>
		<h3><?php echo $this->heading; ?></h3>
		<p class="error"><?php echo $this->error_message(); ?></p>
		<?php if($this->execute === true) exit(PHP_EOL);
	}

	/**
	 * Display the error statically.
	 *
	 * @static
	 * @access public
	 * @param string $message The error message to display.
	 * @param boolean $execute Whether or not to exit.
	 * @param string $heading The heading to display.
	 */
	public static function Display($message, $execute = true, $heading = '') {
		$Error = new error_handler($message, $execute, $heading);
		$Error->Print();
	}
}

set_error_handler(array('error_handler', 'handle_error'));
set_exception_handler(array('error_handler', 'Display'));